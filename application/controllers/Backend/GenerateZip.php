<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class GenerateZip extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('download');  
    }

    // Function to zip user folder and offer it for download
    public function zip_folder($user_id) {
        // Define the folder path
        $user_folder = FCPATH . 'uploads/' . $user_id;

        // Check if the folder exists
        if (!is_dir($user_folder)) {
            // If the folder doesn't exist, show an error
            show_error('The user folder does not exist.', 404);
            return;
        }

        // Create a temporary file path for the ZIP file
        $zip_file = FCPATH . 'uploads/' . $user_id . '/' . $user_id . '_files.zip';

        // Initialize the ZipArchive class
        $zip = new ZipArchive();

        // Open the ZIP file for writing
        if ($zip->open($zip_file, ZipArchive::CREATE) !== TRUE) {
            // If there’s an error opening the file, show an error
            show_error('Could not open ZIP file for writing.');
            return;
        }

        // Recursively add files and directories to the zip
        $this->add_files_to_zip($zip, $user_folder, $user_folder);

        // Close the ZIP archive
        $zip->close();

        // Check if the ZIP file was created
        if (file_exists($zip_file)) {
            // file download in browser
            force_download($zip_file, NULL);

            // Delete the ZIP file after download
            unlink($zip_file);
        } else {
            // If there was an issue creating the zip file, show an error
            show_error('Error creating the ZIP file.');
        }
    }

    // Helper function to add files to the zip
    private function add_files_to_zip($zip, $folder_path, $base_folder_path) {
        // Get all files and directories inside the folder
        $files = scandir($folder_path);

        foreach ($files as $file) {
            // Skip the '.' and '..' directories
            if ($file == '.' || $file == '..') {
                continue;
            }

            // Full path to the file or folder
            $file_path = $folder_path . '/' . $file;

           
            if (is_dir($file_path)) {
                $this->add_files_to_zip($zip, $file_path, $base_folder_path);
            } else {
                // If it's a file, add it to the zip
                $relative_path = substr($file_path, strlen($base_folder_path) + 1);  // Get relative path
                $zip->addFile($file_path, $relative_path);
            }
        }
    }
}
