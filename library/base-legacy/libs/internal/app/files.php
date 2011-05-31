<?


function fixFilesArray(&$files){
    $names = array( 'name' => 1, 'type' => 1, 'tmp_name' => 1, 'error' => 1, 'size' => 1);

    foreach ($files as $key => $part) {
        // only deal with valid keys and multiple files
        $key = (string) $key;
        if (isset($names[$key]) && is_array($part)) {
            foreach ($part as $position => $value) {
                $files[$position][$key] = $value;
            }
            // remove old key reference
            unset($files[$key]);
        }
    }
}