<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfileListModel extends Model
{
    protected $table      = 'user_account';
    protected $primaryKey = 'account_id';

    /**
     * MAIN BASE QUERY
     */
    private function baseQuery(array $filters)
    {
        $db = \Config\Database::connect();
        $b  = $db->table('user_account ua');

        // Joins (latest education / latest work, photos)
        $b->select("
            ua.account_id, ua.user_id, ua.user_name, ua.user_last_name,
            ua.date_of_birth, ua.gender, ua.marital_status, ua.height,
            ua.religion_id, ua.caste_id, ua.dietary_preference,
            ua.current_residence, ua.user_photo,
            ua.user_status, ua.user_status_color,
            ua.approval, ua.visibility_verified, ua.visibility_orange, ua.visibility_defence
        ");

        // Education (one row per user - latest)
        $b->join("
            (SELECT ue1.*
             FROM user_education ue1
             INNER JOIN (
                SELECT user_id, MAX(id) AS max_id
                FROM user_education
                GROUP BY user_id
             ) t ON t.user_id = ue1.user_id AND t.max_id = ue1.id
            ) ue
        ", "ue.user_id = ua.user_id", "left");

        $b->select("ue.highest_education, ue.education_level, ue.profession as edu_profession, ue.income as edu_income");

        // Work (latest)
        $b->join("
            (SELECT uw1.*
             FROM user_work_experience uw1
             INNER JOIN (
                SELECT user_id, MAX(id) AS max_id
                FROM user_work_experience
                GROUP BY user_id
             ) t2 ON t2.user_id = uw1.user_id AND t2.max_id = uw1.id
            ) uw
        ", "uw.user_id = ua.user_id", "left");

        $b->select("uw.designation, uw.company, uw.ctc");

        // Photos (to check WithPhotos)
        $b->join("user_photos up", "up.user_id = ua.user_id AND up.is_active = 1", "left");
        $b->select("COUNT(up.id) as photo_count");

        $b->groupBy("ua.user_id");

        // ---------- APPLY FILTERS ----------
        $this->applyFilters($b, $filters);

        return $b;
    }

    /**
     * APPLY FILTERS
     */
    private function applyFilters($b, array $filters)
    {
        // Hide deleted/blocked etc (you can customize)
        $b->where('ua.hide_profile', 0);

        // Search by Profile Number (assuming ua.account_id or ua.user_id)
        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $b->groupStart()
              ->like('ua.account_id', $s)
              ->orLike('ua.user_id', $s)
              ->groupEnd();
        }

        // Age filter (DOB -> age)
        if (!empty($filters['age'])) {
            [$minAge, $maxAge] = $this->parseRange($filters['age']); // e.g. 18-30
            if ($minAge !== null && $maxAge !== null) {
                // MySQL: TIMESTAMPDIFF(YEAR, dob, CURDATE())
                $b->where("TIMESTAMPDIFF(YEAR, ua.date_of_birth, CURDATE()) >=", (int)$minAge, false);
                $b->where("TIMESTAMPDIFF(YEAR, ua.date_of_birth, CURDATE()) <=", (int)$maxAge, false);
            }
        }

        // Height filter (your UI is feet ranges, DB likely stores cm)
        if (!empty($filters['height'])) {
            [$minCm, $maxCm] = $this->heightRangeToCm($filters['height']); // "5.1-5.5"
            if ($minCm !== null && $maxCm !== null) {
                $b->where('ua.height >=', $minCm);
                $b->where('ua.height <=', $maxCm);
            } elseif ($minCm !== null && $maxCm === null) {
                $b->where('ua.height >=', $minCm);
            }
        }

        // Religion
        if (!empty($filters['religion']) && $filters['religion'] !== 'Any') {
            if ($filters['religion'] === 'Other') {
                // if you store other religion in a column -> change here
                // Example: ua.other_religion
                if (!empty($filters['other_religion'])) {
                    $b->like('ua.religion_id', $filters['other_religion']); // CHANGE if you have other_religion column
                }
            } else {
                // If you store religion name in religion_id as string, ok.
                // If you store numeric ids, then map name->id before calling model.
                $b->where('ua.religion_id', $filters['religion']);
            }
        }

        // Caste
        if (!empty($filters['caste'])) {
            if ($filters['caste'] === 'Other') {
                // Example: ua.other_caste
                if (!empty($filters['other_caste'])) {
                    $b->like('ua.caste_id', $filters['other_caste']); // CHANGE if you have other_caste column
                }
            } else {
                $b->where('ua.caste_id', $filters['caste']);
            }
        }

        // Marital status
        if (!empty($filters['marital_status']) && $filters['marital_status'] !== 'any') {
            $b->where('ua.marital_status', $filters['marital_status']);
        }

        // Qualification (education)
        if (!empty($filters['qualification'])) {
            // store mapping in education.highest_education / education_level / degree; adjust as per your db
            $b->groupStart()
              ->where('ue.highest_education', $filters['qualification'])
              ->orWhere('ue.education_level', $filters['qualification'])
              ->groupEnd();
        }

        // Manglik
        if (!empty($filters['mangal_status'])) {
            $b->where('ua.manglik_status', $filters['mangal_status']);
        }

        // Profession (from user_account or education)
        if (!empty($filters['profession']) && $filters['profession'] !== 'other') {
            $b->groupStart()
              ->where('ua.profession', $filters['profession'])
              ->orWhere('ue.profession', $filters['profession'])
              ->orWhere('uw.designation', $filters['profession'])
              ->groupEnd();
        }

        // Living in (example: ua.current_residence contains India/Overseas)
        if (!empty($filters['living_in'])) {
            if ($filters['living_in'] === 'india') {
                $b->like('ua.current_residence', 'India');
            } elseif ($filters['living_in'] === 'overseas') {
                $b->notLike('ua.current_residence', 'India');
            }
        }

        // Dietary
        if (!empty($filters['dietary_preference']) && $filters['dietary_preference'] !== 'doesnt_matter') {
            $b->where('ua.dietary_preference', $filters['dietary_preference']);
        }

        // Income/CTC filter (using uw.ctc primarily)
        if (!empty($filters['income'])) {
            [$min, $max] = $this->incomeRangeToNumber($filters['income']); // returns annual value in LPA or numeric
            if ($min !== null && $max !== null) {
                $b->where('uw.ctc >=', $min);
                $b->where('uw.ctc <=', $max);
            } elseif ($min !== null && $max === null) {
                $b->where('uw.ctc >=', $min);
            }
        }

        // Badge filter (adjust to your real meaning)
        // Assumption:
        // Green  => ua.user_status_color = 'Green'
        // Orange => ua.user_status_color = 'Orange' OR ua.visibility_orange = 1
        // Blue/Verified => ua.visibility_verified = 1 OR ua.approval = 1
        if (!empty($filters['badge']) && is_array($filters['badge']) && !in_array('All', $filters['badge'], true)) {
            $b->groupStart();
            foreach ($filters['badge'] as $badge) {
                if ($badge === 'Green') {
                    $b->orWhere('ua.user_status_color', 'Green');
                } elseif ($badge === 'Orange') {
                    $b->orGroupStart()
                      ->where('ua.user_status_color', 'Orange')
                      ->orWhere('ua.visibility_orange', 1)
                      ->groupEnd();
                } elseif ($badge === 'Blue') {
                    $b->orGroupStart()
                      ->where('ua.visibility_verified', 1)
                      ->orWhere('ua.approval', 1)
                      ->groupEnd();
                }
            }
            $b->groupEnd();
        }

        // Show with photos
        if (!empty($filters['show']) && $filters['show'] === 'WithPhotos') {
            $b->having('photo_count >', 0);
        }

        // Sorting
        $sort = $filters['sort'] ?? 'date_newest';
        if ($sort === 'date_newest') {
            $b->orderBy('ua.register_date', 'DESC');
        } elseif ($sort === 'date_oldest') {
            $b->orderBy('ua.register_date', 'ASC');
        } elseif ($sort === 'age_youngest') {
            $b->orderBy('ua.date_of_birth', 'DESC'); // younger => later DOB
        } elseif ($sort === 'age_oldest') {
            $b->orderBy('ua.date_of_birth', 'ASC');
        } else {
            $b->orderBy('ua.register_date', 'DESC');
        }
    }

    public function countProfiles(array $filters): int
    {
        $b = $this->baseQuery($filters);
        // Count distinct users after groupBy
        $result = $b->get()->getResultArray();
        return count($result);
    }

    public function getProfiles(array $filters, int $limit, int $offset): array
    {
        $b = $this->baseQuery($filters);
        $b->limit($limit, $offset);
        return $b->get()->getResultArray();
    }

    private function parseRange(string $range): array
    {
        // "18-30"
        if (strpos($range, '-') !== false) {
            [$a, $b] = explode('-', $range);
            return [is_numeric($a) ? (int)$a : null, is_numeric($b) ? (int)$b : null];
        }
        return [null, null];
    }

    private function heightRangeToCm(string $range): array
    {
        // UI: "4.0-4.5", "6.1+"
        $range = trim($range);

        if (str_ends_with($range, '+')) {
            $minFt = (float) rtrim($range, '+');
            $minCm = $this->feetToCm($minFt);
            return [$minCm, null];
        }

        if (strpos($range, '-') !== false) {
            [$minFt, $maxFt] = explode('-', $range);
            $minCm = $this->feetToCm((float)$minFt);
            $maxCm = $this->feetToCm((float)$maxFt);
            return [$minCm, $maxCm];
        }

        return [null, null];
    }

    private function feetToCm(float $ftDotIn): int
    {
        // "5.1" => 5 feet 1 inch
        $feet  = (int) floor($ftDotIn);
        $inch  = (int) round(($ftDotIn - $feet) * 10); // 0.1 => 1 inch
        $totalInches = ($feet * 12) + $inch;
        return (int) round($totalInches * 2.54);
    }

    private function incomeRangeToNumber(string $key): array
    {
        // You must match your uw.ctc unit.
        // If uw.ctc stores LPA numeric: use LPA values below.
        // If uw.ctc stores annual rupees, multiply accordingly.
        switch ($key) {
            case 'below_10': return [0, 10];
            case '10_20':    return [10, 20];
            case '20_30':    return [20, 30];
            case '30_40':    return [30, 40];
            case '40_50':    return [40, 50];
            case '50_75':    return [50, 75];
            case '75_99':    return [75, 99];
            case '1cr_plus': return [100, null]; // 1 Cr ~ 100 LPA
            default:         return [null, null];
        }
    }
}
