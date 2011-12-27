<?php
/*
Plugin Name: Abbreviations for Wordpress
Plugin URI: http://online-source.net/2011/12/25/abbreviations-for-wordpress
Description: Wrap abbreviations for search engine optimization and support other applications  
Version: 1.1
Author: MrXHellboy
Author URI: http://online-source.net
*/

class os_abbreviations {
    function add_abbreviations($content){
        $abbr = get_option('os_abbreviations');
            foreach ($abbr as $abbreviation => $array) {
                $lang = ($array[1] == '') ? '' : 'lang="'. $array[1] .'"';
                $content = preg_replace('@([^<abbr.*>]\s+)'. $abbreviation .'(\s+[^</abbr])@si', '$1<abbr title="'. $array[0] .'" '. $lang .'>'. $abbreviation .'</abbr>$2' , $content);
            }
        return $content;
    }

    function add_control_page() {
        add_options_page('Abbreviations', 'Abbreviations', 10, 'abbreviations', array('os_abbreviations', 'abbreviations_admin_panel'));
    }

    function update_abbreviations($abbreviations){
        $save = array();
        for($i = 0; $i < count($abbreviations['abbr']); ++$i) {
            $abbr = trim($abbreviations['abbr'][$i]);
            $desc = trim($abbreviations['desc'][$i]);
            $lang = trim($abbreviations['lang'][$i]);
              
			if ($abbr == '' || $desc == '') { 
                continue; 
            } else { 
                $save[$abbr] = array($desc, $lang); 
            }
        }
            update_option('os_abbreviations', $save);
    }
    
    function get_abbreviations(){
        $abbreviations = get_option('os_abbreviations');
        $return = '';
            if (!empty($abbreviations)){
                    foreach ($abbreviations as $abbr => $array){
                        $return .= '<tr><td><input type="text" name="abbreviations[abbr][]" value="'. $abbr .'" class="large-text" /></td>
                        <td><input type="text" name="abbreviations[desc][]" value="'. $array[0] .'" class="large-text" /></td>
                        <td><input type="text" name="abbreviations[lang][]" value="'. $array[1] .'" class="large-text" /></td>
                        </tr>';
                    }                
            }
        return $return;
    }
        
    function abbreviations_admin_panel(){
    ?>
    <div class="wrap">
        <h2>Abbreviations</h2>
		
        <form method="post" action="options-general.php?page=abbreviations" class="form-table">
            <table>
                <tr>
                    <th>Abbreviation <small>(required)</small></th>
                    <th>Description <small>(required)</small></th>
                    <th title="Specify a language code for the content f.i. nl, de or en: ">Language code <small>(optional)</small></th>
                </tr>
                    <?php echo os_abbreviations::get_abbreviations(); ?>
                <tr>
                    <td>
                        <input type="text" name="abbreviations[abbr][]" value="" class="large-text" />
                    </td>
                    <td>
                        <input type="text" name="abbreviations[desc][]" value="" class="large-text" />
                    </td>
                    <td>
                        <input type="text" name="abbreviations[lang][]" value="" class="large-text" />
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="save_abbr" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>
        </form>
    </div>
    <?php
    }    
}

if (isset($_POST['save_abbr']) && class_exists('os_abbreviations')){
    $instance = new os_abbreviations;
        $instance->update_abbreviations($_POST['abbreviations']);
}

add_action('admin_menu', array('os_abbreviations', 'add_control_page'));
add_filter('the_content', array('os_abbreviations', 'add_abbreviations'));
?>