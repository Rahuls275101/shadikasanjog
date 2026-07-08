<?php
namespace App\Models;

use CodeIgniter\Model;
use DateTime;
use Exception;

class Profilemodel extends Model
{
    protected $table = 'user_account';
    protected $primaryKey = 'account_id';
    protected $returnType = 'array';
    protected $db;

    // Search columns (LIKE)
    protected $column_search = [
        'user_account.user_id',
        'user_account.user_name',
        'user_account.user_last_name',
        'user_account.user_email',
        'user_account.user_phone',
        'user_education.highest_education',
        'user_education.profession',
        'user_family_background.family_type',
        'user_partner_preferences.about_partner',
        'user_account.current_residence',
        'user_account.family_home'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    /**
     * Convert UI height ranges (feet-inches buckets) to CM range.
     */
    private function heightRangeToCm(?string $range): ?array
    {
        if (empty($range)) return null;

        $map = [
            '4.0-4.5' => [122, 137],
            '4.6-5.0' => [138, 152],
            '5.1-5.5' => [153, 167],
            '5.6-6.0' => [168, 183],
            '6.1+'    => [185, 999],
        ];

        return $map[$range] ?? null;
    }

    /**
     * Apply opposite gender filter for logged-in user
     */
    private function applyOppositeGenderFilter($builder): void
    {
        try {
            $session = session();
            $usersession = $session->get('loggedin');

            if ($usersession && !empty($usersession['user_gender'])) {
                $currentGender = $usersession['user_gender'];
                if ($currentGender === 'Male') {
                    $builder->where('user_account.gender', 'Female');
                } elseif ($currentGender === 'Female') {
                    $builder->where('user_account.gender', 'Male');
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Error in applyOppositeGenderFilter: ' . $e->getMessage());
        }
    }

    /**
     * Main query builder with joins + filters
     */
   private function _get_datatables_query($request)
{
        $session     = session();
        $usersession = $session->get('loggedin');
        $userId = $usersession['user_id'];
        
          $userdata =   $this->db->table('user_account')
            ->where('account_id', $usersession['user_id'])
            ->get()
            ->getRow();
          
    
    
    $builder = $this->db->table($this->table);

    $builder->select('
        user_account.*,
        user_education.highest_education,
        user_education.profession AS edu_profession,
        user_education.income AS edu_income,
        user_family_background.family_type,
        user_partner_preferences.about_partner,
        user_photos.id AS photo_id
    ');

    $builder->join('user_education', 'user_education.user_id = user_account.account_id', 'left');
    $builder->join('user_family_background', 'user_family_background.user_id = user_account.account_id', 'left');
    $builder->join('user_partner_preferences', 'user_partner_preferences.user_id = user_account.account_id', 'left');
    $builder->join('user_photos', 'user_photos.user_id = user_account.account_id AND user_photos.is_active = 1', 'left');

    $fields = $this->db->getFieldNames('user_account');

    // Base condition
    if (in_array('approval', $fields)) {
        $builder->where('user_account.approval', 'Approved');
    }

    if (in_array('hide_profile', $fields)) {
        $builder->where('user_account.hide_profile', 0);
    }

    // Opposite gender
    $this->applyOppositeGenderFilter($builder);

    // =========================
    // 🎯 GET FILTERS
    // =========================
    $isAdvanced = (int)$request->getPost('is_advanced');

    // Decide source
    $ageRange    = $isAdvanced ? $request->getPost('age_adv') : $request->getPost('age_quick');
    $heightRange = $isAdvanced ? $request->getPost('height_adv') : $request->getPost('height_quick');
    $religion    = $isAdvanced ? $request->getPost('religion_adv') : $request->getPost('religion_quick');
    $caste       = $isAdvanced ? $request->getPost('caste_adv') : $request->getPost('caste_quick');

    // Common filters
    $search        = $request->getPost('search');
    $maritalStatus = $request->getPost('marital_status');
    $qualification = $request->getPost('qualification');
    $mangalStatus  = $request->getPost('mangal_status');
    $profession    = $request->getPost('profession');
    $livingIn      = $request->getPost('living_in');
    $income        = $request->getPost('income');
    $dietaryPref   = $request->getPost('dietary_preference');
    $badge         = $request->getPost('badge');
    $show          = $request->getPost('show');
    $sort          = $request->getPost('sort');

    // =========================
    // 🔍 SEARCH
    // =========================
    if (!empty($search)) {
        $builder->groupStart();
        foreach ($this->column_search as $col) {
            $builder->orLike($col, $search);
        }
        $builder->groupEnd();
    }

    // =========================
    // 🎂 AGE FILTER
    // =========================
    if (!empty($ageRange) && strpos($ageRange, '-') !== false) {
        [$min, $max] = explode('-', $ageRange);

        $builder->where("TIMESTAMPDIFF(YEAR, user_account.date_of_birth, CURDATE()) >=", (int)$min);
        $builder->where("TIMESTAMPDIFF(YEAR, user_account.date_of_birth, CURDATE()) <=", (int)$max);
    }
    
    
      $builder->where('user_account.hide_profile', 0);
    
    
        if (
            $userdata->membership_id == 2 &&
            $userdata->membership_status == 'Active' &&
            $userdata->batch == 'Green'
        ) {
            $builder->whereIn('user_account.visibility_defence', [0, 1]);
         
        }
        elseif (
            $userdata->membership_id == 3 &&
            $userdata->membership_status == 'Active' &&
            $userdata->batch == 'Orange'
        ) {
         
            $builder->whereIn('user_account.visibility_orange', [0, 1]);
        }
        else {
            $builder->where('user_account.visibility_defence', 0);
            $builder->where('user_account.visibility_orange', 0);
        }
        
        
        

    // =========================
    // 📏 HEIGHT FILTER
    // =========================
    $cm = $this->heightRangeToCm($heightRange);
    if ($cm) {
        $builder->where("CAST(user_account.height AS UNSIGNED) >=", $cm[0]);
        $builder->where("CAST(user_account.height AS UNSIGNED) <=", $cm[1]);
    }

    // =========================
    // 🕌 RELIGION
    // =========================
    if (!empty($religion) && $religion !== 'Any') {
        $builder->where('user_account.religion_id', $religion);
    }

    // =========================
    // 🧬 CASTE (NEW FIX)
    // =========================
    if (!empty($caste) && $caste !== 'Any' && in_array('caste', $fields)) {
        $builder->where('user_account.caste', $caste);
    }

    // =========================
    // 💍 MARITAL STATUS
    // =========================
    if (!empty($maritalStatus) && $maritalStatus !== 'any') {
        $builder->where('user_account.marital_status', $maritalStatus);
    }

    // =========================
    // 🎓 EDUCATION
    // =========================
    if (!empty($qualification)) {
        $builder->where('user_education.highest_education', $qualification);
    }

    // =========================
    // 💼 PROFESSION
    // =========================
    if (!empty($profession)) {
        $builder->where('user_education.profession', $profession);
    }

    // =========================
    // 💰 INCOME
    // =========================
    if (!empty($income)) {
        $builder->where('user_education.income', $income);
    }

    // =========================
    // 🍽 DIET
    // =========================
    if (!empty($dietaryPref) && $dietaryPref !== 'Doesn’t Matter') {
        $builder->where('user_account.dietary_preference', $dietaryPref);
    }

    // =========================
    // 🔮 MANGLIK
    // =========================
    if (!empty($mangalStatus)) {
        $builder->where('user_account.manglik_status', $mangalStatus);
    }

    // =========================
    // 🌍 LOCATION
    // =========================
    if ($livingIn === 'india') {
        $builder->like('user_account.current_residence', 'India');
    } elseif ($livingIn === 'overseas') {
        $builder->notLike('user_account.current_residence', 'India');
    }

    // =========================
    // 🏷 BADGE
    // =========================
    if (is_array($badge) && !in_array('All', $badge)) {
        $builder->whereIn('user_account.batch', $badge);
    }

    // =========================
    // 📸 PHOTO
    // =========================
    if ($show === 'WithPhotos') {
        $builder->where('user_photos.id IS NOT NULL');
    }

    // =========================
    // 🔃 SORT
    // =========================
    switch ($sort) {
        case 'age_youngest':
            $builder->orderBy('date_of_birth', 'DESC');
            break;
        case 'age_oldest':
            $builder->orderBy('date_of_birth', 'ASC');
            break;
        case 'date_oldest':
            $builder->orderBy('register_date', 'ASC');
            break;
        default:
            $builder->orderBy('register_date', 'DESC');
    }

    $builder->groupBy('user_account.account_id');

    return $builder;
}

    /**
     * Count total results
     */
    public function count_all_frontend($request)
    {
        try {
            $builder = $this->_get_datatables_query($request);
            return $builder->countAllResults();
        } catch (Exception $e) {
            log_message('error', 'Error in count_all_frontend: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Fetch paginated data with joined info
     */
  public function fetch_data($limit, $start, $request)
{
    
       $session     = session();
        $usersession = $session->get('loggedin');
        $userId = $usersession['user_id'];
        
          $userdata =   $this->db->table('user_account')
            ->where('account_id', $usersession['user_id'])
            ->get()
            ->getRow();
            
    try {
        $commanmodel = new \App\Models\Commanmodel();

        $builder = $this->_get_datatables_query($request);
        $builder->limit((int)$limit, (int)$start);
        $query = $builder->get();
        
       

        $output = '';
        $totalRecords = $this->count_all_frontend($request);
        
        $start_record = $start + 1;
        $end_record = min($start + $limit, $totalRecords);
        
        if ($totalRecords > 0) {
            $headoutput = "Showing $start_record - $end_record of $totalRecords results";
        } else {
            $headoutput = 'No results found';
        }

        if ($query && $query->getNumRows() > 0) {
            foreach ($query->getResult() as $row) {
                
                
$searchValue = $userdata->user_id;

$visibilityProfiles = trim((string)$row->visibility_following_profiles);

if (
    empty($visibilityProfiles) ||
    in_array(
        strtoupper($searchValue),
        array_map(
            'strtoupper',
            array_map('trim', explode(',', $visibilityProfiles))
        )
    )
) {
                // Age calculation
                $age = 'Not specified';
                if (!empty($row->date_of_birth)) {
                    try {
                        $birthDate = new DateTime($row->date_of_birth);
                        $today = new DateTime();
                        $age = $today->diff($birthDate)->y;
                    } catch (Exception $e) {
                        $age = 'Invalid date';
                    }
                }

                $height = !empty($row->height) ?   esc($row->height) . ' ft ' . esc($row->height_inch) . ' inch' : 'Not specified';
                
                // Get education data for this user
                $education_data = $this->db->table('user_education ue')
                    ->select('ue.*, eq.qualification_name')
                    ->join('education_qualifications eq', 'eq.id = ue.education_level', 'left')
                    ->where('ue.user_id', $row->account_id)
                  
                    ->orderBy('ue.education_level', 'DESC') // Highest education level first
                    ->get()
                    ->getResultArray();
                
                // Get highest education
                $education = 'Not specified';
                $highestEducation = !empty($education_data) ? $education_data[0] : null;
                
                if ($highestEducation) {
                    // Format education string based on available data
                    $edu_parts = [];
                    if (!empty($highestEducation['qualification_name'])) {
                        $edu_parts[] = $highestEducation['qualification_name'] ?? $highestEducation['qualification_name'];
                    }
                    if (!empty($highestEducation['degree'])) {
                        $edu_parts[] = $highestEducation['degree'];
                    }
                    if (!empty($highestEducation['institution'])) {
                        $edu_parts[] = 'from ' . $highestEducation['institution'];
                    }
                    
                    $education = !empty($edu_parts) ? implode(' ', $edu_parts) : 'Not specified';
                }
                
                // Get profession name
                $profession_name = $row->other_profession ?? '';
                if (!empty($row->edu_profession)) {
                    try {
                        $profession_data = $commanmodel->get_single_query(
                            'profession_categories',
                            ['id' => $row->edu_profession]
                        );
                        if ($profession_data && isset($profession_data->category_name)) {
                            $profession_name = $profession_data->category_name;
                        }
                    } catch (Exception $e) {
                        log_message('error', 'Error fetching profession: ' . $e->getMessage());
                    }
                }
                
                // Location
                $location = 'Not specified';
                if (!empty($row->current_residence)) {
                    $location = $row->current_residence;
                } elseif (!empty($row->family_home)) {
                    $location = $row->family_home;
                }

                // Badge class
                $badgeClass = '';
                if (!empty($row->batch)) {
                    $color = strtolower(trim($row->batch));
                    if ($color === 'green') $badgeClass = 'greenbage';
                    if ($color === 'blue') $badgeClass = 'bluebage';
                    if ($color === 'orange') $badgeClass = 'orangebage';
                }

                // Profile image
                $profile_img = $commanmodel->profile_image($row->account_id);
                $profileUrl = base_url('/profile-details/' . $row->user_id);

                $verifiedBadge = '';
                if (!empty($row->verified) && strtolower(trim($row->verified)) === 'yes') {
                    $verifiedBadge = '<span class="verified-badge">Verified</span>';
                }

                $output .= '
                <li>
                    <div class="all-pro-box ' . esc($badgeClass) . '" data-useravil="avilno" data-aviltxt="Offline">
                        <div class="pro-img">
                            <a href="' . $profileUrl . '">
                                <img src="' . esc($profile_img) . '" alt="' . esc($row->user_name) . '">
                            </a>
                            <div class="pro-ave" title="User currently available">
                                <span class="pro-ave-yes"></span>
                            </div>
                        </div>
                        <div class="pro-detail">
                            <h4>
                                <a href="' . $profileUrl . '">' . esc($row->user_name . ' ' . $row->user_last_name) . ' <br> ' . esc($row->user_id) . '</a>
                                <br>
                                ' . $verifiedBadge . '
                            </h4>
                        
                            <div class="pro-bio">
                                <span><i class="fa fa-graduation-cap"></i> ' . esc($education) . '</span>
                                <span><i class="fa fa-briefcase"></i> ' . esc($profession_name) . '</span>
                                <span><i class="fa fa-calendar"></i> ' . esc($age) . ' Years</span>
                                <span><i class="fa fa-arrows-v"></i> ' . esc($height) . '</span>
                                <span><i class="fa fa-map-marker"></i> ' . esc($location) . '</span>
                                <div class="send"><span class="btn-interest" data-userid="' . $row->account_id . '">Send Interest</span></div>
                                <div class="contact"><span class="viewContactBtn" data-user-id="' . $row->account_id . '">Contact</span></div>
                            </div>
                        </div>
                    </div>
                </li>';
            }
            }
        } else {
            $output = '<li><div class="col-md-12 text-center py-5"><h3>No result found!</h3></div></li>';
        }

        return [
            'output' => $output, 
            'headoutput' => $headoutput
        ];
        
    } catch (Exception $e) {
        log_message('error', 'Error in fetch_data: ' . $e->getMessage());
        return [
            'output' => '<li><div class="col-md-12 text-center py-5"><h3>Error loading results: ' . $e->getMessage() . '</h3></div></li>',
            'headoutput' => 'Error loading results'
        ];
    }
}
}