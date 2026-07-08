<?php

namespace App\Controllers;

require_once(APPPATH . "Libraries/dompdf/vendor/autoload.php");
require_once(APPPATH . "Libraries/phpword/vendor/autoload.php");

use CodeIgniter\Controller;
use App\Models\Commanmodel;
use App\Models\Productmodel;
use App\Models\Usermodel;
use App\Libraries\Cart;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpWord\IOFactory;

class Test extends BaseController
{
    public function index()
    {
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Upload Word File</title>
        </head>
        <body>

            <h2>Upload Word File and Convert to PDF</h2>

            <form action="' . site_url('test/submit') . '" method="post" enctype="multipart/form-data">
                <label for="file">Choose a Word file:</label>
                <input type="file" name="file" id="file" required>
                <br><br>
                <button type="submit">Upload and Convert</button>
            </form>

        </body>
        </html>';
    }

    public function submit()
    {
        // Check if a file was uploaded
        $file = $this->request->getFile('file');

        if ($file->isValid() && !$file->hasMoved()) {
            // Check if file is a Word file (docx)
            $fileType = $file->getClientMimeType();
            if ($fileType !== 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
                return redirect()->to('/upload/error')->with('error', 'Invalid file type. Please upload a .docx file.');
            }

            // Generate a random file name
            $newName = $file->getRandomName();

            // Move the uploaded file to the 'assets/uploads/' folder
            $file->move('assets/uploads/', $newName);

            // Get the full path of the uploaded file
            $wordFilePath = 'assets/uploads/' . $newName;

            // Convert the Word file to PDF and get the generated PDF path
            $pdfFilePath = $this->convertWordToPdf($wordFilePath);

            // Return the generated PDF file for download
            return $this->response->download($pdfFilePath, null)->setFileName('converted.pdf');
        }

        // Handle file upload error
        return redirect()->to('/upload/error')->with('error', 'File upload failed.');
    }

  private function convertWordToPdf($wordFilePath)
{
    // Load the Word document using PHPWord
    $phpWord = IOFactory::load($wordFilePath);

    // Extract images and check for unsupported formats like EMF
    foreach ($phpWord->getSections() as $section) {
        foreach ($section->getElements() as $element) {
            if ($element instanceof \PhpOffice\PhpWord\Element\Image) {
                $imagePath = $element->getSource();

                // Check if the image is in EMF format
                if (pathinfo($imagePath, PATHINFO_EXTENSION) === 'emf') {
                    // Try to replace the EMF image with a fallback (e.g., PNG or JPG)
                    $newImagePath = $this->replaceEmfWithFallback($imagePath);

                    // Set the new image source
                    if ($newImagePath !== $imagePath) {
                        $element->setSource($newImagePath);
                    } else {
                        // If no replacement is found, remove the image
                        $element->setSource(null);
                    }
                }
            }
        }
    }

    // Save the Word document as HTML temporarily
    $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
    $htmlFile = tempnam('assets/img/', 'phpword') . '.html';
    $htmlWriter->save($htmlFile);

    // Initialize Dompdf
    $dompdf = new Dompdf();
    $dompdf->loadHtml(file_get_contents($htmlFile));

    // Render PDF
    $dompdf->render();

    // Define the path where the PDF will be saved
    $pdfFilePath = 'assets/img/' . uniqid('converted_', true) . '.pdf';

    // Save the generated PDF to the specified path
    file_put_contents($pdfFilePath, $dompdf->output());

    // Clean up the temporary HTML file
    unlink($htmlFile);

    // Return the path of the generated PDF
    return $pdfFilePath;
}

// Function to handle EMF images (replace or remove them)
private function replaceEmfWithFallback($emfFilePath)
{
    // Generate a new image file path
    $fallbackImagePath = 'assets/img/' . uniqid('fallback_', true) . '.png';

    // If you have a way to convert the EMF to a PNG (using external tools), you can convert it here.
    // For now, we're assuming a simple case where we replace it with a fallback image
    // You can use ImageMagick or another tool for conversion as shown before.

    // If the EMF image cannot be converted, return a default fallback image
    $defaultFallbackImage = 'assets/img/fallback.png'; // Provide your fallback PNG image

    // Check if the original EMF file exists, if not use the default fallback image
    if (file_exists($emfFilePath)) {
        // Optionally, use ImageMagick or another method to convert EMF to PNG
        // exec("convert \"$emfFilePath\" \"$fallbackImagePath\"");
        return $fallbackImagePath;
    }

    // Return the default fallback image if no conversion is possible
    return $defaultFallbackImage;
}

}
