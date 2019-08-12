<?php


/************************************************************
*   @function scan
*   @description scan the file and folder [Recursively]
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @return files [array]
************************************************************/
function scan($dir){
  $files = array();

  // Is there actually such a folder/file?
  if(file_exists($dir)){
    foreach(scandir($dir) as $f) {
      if(!$f || $f[0] == '.') {
        continue; // Ignore hidden system files
      }
      if(is_dir($dir . '/' . $f)) {
        // The path is a folder
        $files[] = array(
          "name" => $f,
          "type" => "folder",
          "path" => $dir . '/' . $f,
          "items" => scan($dir . '/' . $f) // Recursively get the contents of the folder
        );
      } else {
        // It is a file
        $files[] = array(
          "name" => $f,
          "type" => "file",
          "path" => $dir . '/' . $f,
          "size" => filesize($dir . '/' . $f) // Gets the size of this file
        );
      } //if
    } // foreach
  } // if

  return $files;
}



/************************************************************
*   @function clean_path
*   @description remove unnecessary thing from path
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @perm path    [string path of file/folder]
*   @return path [string]
************************************************************/
function clean_path($path) {

  $path = str_replace(' ', '-', $path); // Replaces all spaces with hyphens.
  $path = str_replace('_', '-', $path); // Replaces all spaces with hyphens.
  $path = preg_replace('/[^A-Za-z0-9\-]/', '-', $path); // Removes special chars.
  $path = preg_replace('/-+/', '-', $path); // Replaces multiple hyphens with single one.
  $path = trim($path, '-'); // Remove first or last -
  $path = strtolower($path); // lowercase

  return $path;
}



/************************************************************
*   @function checkValueExistsInArray
*   @description 
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @return 
************************************************************/
function aione_data_table($headers, $data, $id='aione-', $class = 'compact'){  
    $columns = array();
    foreach ($headers as $key => $header){
        $columns[] = clean_class($header);
    }

    $output = "";
    $output .= '<div class="aione-search aione-table" >';
    $output .= '<div class="field">';
    $output .= '<input autofocus type="text" class="aione-search-input" data-search="'.implode(' ',$columns).'" placeholder="Search">';
    $output .= '</div>';
    $output .= '<div class="clear"></div>';
    $output .= '<table class="'.$class.'">';
    $output .= '<thead>';
    $output .= '<tr>';
    foreach ($headers as $key => $header){
        $output .= '<th class="aione-sort-button" data-sort="'.$columns[$key].'">'.$header.'</th>';
    }
    $output .= '</tr>';
    $output .= '</thead>';
    $output .= '<tbody class="aione-search-list">';
    if(!empty($data)){
        foreach ($data as $record_key => $record){
            $output .= '<tr>';
            foreach ($record as $key => $value){
                $output .= '<td class="'.$columns[$key].'">'.$value.'</td>';
            }
            $output .= '</tr>';
        }
    }
    $output .= '</tbody>';
    $output .= '</table>';
    $output .= '</div>';
    return $output;
}

/************************************************************
*   @function draw_tree
*   @description Generate Tree
*   @access public
*   @since  1.0.0.0
*   @author OXO Solutions®(oxosolutions.com)
*   @return output [string]
************************************************************/
function draw_tree( $path ) {
  $nodes = get_tree( $path );
  $ds = directory_separator();

  if( !empty( $nodes ) && is_array( $nodes ) ){
    $output = '';
    $output .= '<ul class="nodes">';
    foreach ( $nodes as $key => $node ) {
      
      $output .= '<li class="node folder">';

      $output .= '<div class="title">';
      $output .= $node['name'];
      $output .= '</div>';

      $output .= '<div class="size">';
      $output .= get_directory_size( $node['path'] );
      $output .= '</div>';

// <?php echo(); 
      $total_files_counter = count_files($node['path']). ' Files';
      $total_folder_counter = count( $node['items'] ) . ' Folders';

      $output .= '<div class="items">';      
      $output .= $total_folder_counter . ', ' . $total_files_counter;      
      $output .= '</div>';

      $output .= '<div class="actions">';
      $output .= '<a href="manage-cron.php?path=' . $node['path'] . '"><i class="ion-ios-settings"></i></a>';
      $output .= '</div>';

      $output .= draw_tree( $node['path'] );
      
      $output .= '</li>';

    } // foreach $nodes
    $output .= '</ul>';

    return $output;
  } //if
}


$dir = '../files';
// $nodes = scan( $dir );

$nodes = draw_tree( $dir );

echo $nodes;


/*
foreach ( $nodes as $key => $node ) {


}

echo "<pre>";
print_r( $nodes );
echo "</pre>";
*/

