<?php
add_action( 'admin_menu', 'apm_add_admin_menu' );
add_action( 'admin_init', 'apm_settings_init' );


function apm_add_admin_menu(  ) {

  add_options_page( __('Automatic Post Media', 'automatic-post-media'), __('Automatic Post Media', 'automatic-post-media'), 'manage_options', '_automatic_post_media', 'apm_options_page' );

}


function apm_settings_init(  ) {

  register_setting( 'apmPluginPage', 'apm_settings' );

  add_settings_section(
    'apm_apmPluginPage_section',
    __( 'Change Automatic Post Options', 'automatic-post-media' ),
    'apm_settings_section_callback',
    'apmPluginPage'
  );


  add_settings_field(
    'apm_select_author',
    __( 'Choose Author', 'automatic-post-media' ),
    'apm_select_author_render',
    'apmPluginPage',
    'apm_apmPluginPage_section'
  );

  add_settings_field(
    'apm_select_category',
    __( 'Choose Category', 'automatic-post-media' ),
    'apm_select_category_render',
    'apmPluginPage',
    'apm_apmPluginPage_section'
  );

  add_settings_field(
    'apm_select_title',
    __( 'Choose Title Style', 'automatic-post-media' ),
    'apm_select_title_render',
    'apmPluginPage',
    'apm_apmPluginPage_section'
  );

  add_settings_field(
    'apm_textarea_content',
    __( 'Automatic Post Content', 'automatic-post-media' ),
    'apm_textarea_content_render',
    'apmPluginPage',
    'apm_apmPluginPage_section'
  );


}


function apm_select_author_render(  ) {
  $options = get_option( 'apm_settings' );
  $authors = get_users( array( 'fields' => array( 'display_name','id' ) ) )
  ?>
  <select name='apm_settings[apm_select_author]'>
    <?php foreach($authors as $user){
      ?>
      <option value='<?php echo $user->id;?>' <?php selected( $options['apm_select_author'], $user->id ); ?>><?php echo $user->display_name ?></option>
      <?php
    }?>
    </select>

<?php

}

function apm_select_title_render(  ) {

  $options = get_option( 'apm_settings' );
  ?>
  <select name='apm_settings[apm_select_title]'>
    <option value='1' <?php selected( $options['apm_select_title'], 1 ); ?>>[EXT]FILENAME</option>
    <option value='2' <?php selected( $options['apm_select_title'], 2 ); ?>>FILENAME.EXT</option>
    <option value='3' <?php selected( $options['apm_select_title'], 2 ); ?>>FILENAME</option>
  </select>

<?php

}

function apm_select_category_render(  ) {
  $categories = get_categories( array('hide_empty'=> 0));
  $options = get_option( 'apm_settings' );
  ?>
  <select name='apm_settings[apm_select_category]'>
      <?php
      foreach ($categories as $category) {
        ?>
        <option value='<?php echo $category->term_id;?>' <?php selected( $options['apm_select_category'], $category->term_id); ?>><?php echo $category->name;?></option>
        <?php
      }?>
  </select>

<?php

}


function apm_textarea_content_render(  ) {

  $options = get_option( 'apm_settings' );
  if (!$options['apm_textarea_content']){
    $options['apm_textarea_content'] = "<a href='[url]'>[name]</a>";
  }
  ?>
  <textarea cols='40' rows='5' name='apm_settings[apm_textarea_content]'><?php echo $options['apm_textarea_content']; ?></textarea>
  <p><code>[url]</code>文件的下载链接</br><code>[name]</code>文件名</p>
  <?php

}


function apm_settings_section_callback(  ) {

  echo __( 'Automatic Post Media Can Post Media into Post', 'automatic-post-media' );

}


function apm_options_page(  ) {

  ?>
  <form action='options.php' method='post'>

    <h2><?php echo __('Automatic Post Media','automatic-post-media')?></h2>

    <?php
    settings_fields( 'apmPluginPage' );
    do_settings_sections( 'apmPluginPage' );
    submit_button();
    ?>

  </form>
  <?php

}


