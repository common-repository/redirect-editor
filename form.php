<?php if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<style> body { background-color:#fff; }</style>
<div id="banner">
	<div class='wrap'>
		<form action="https://www.paypal.com/donate" method="post" target="_top">
			<input type="hidden" name="hosted_button_id" value="ZGH6WYRFJXMG8" />
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
			<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
		</form>
	</div>
</div>

Submit your auto-generated sitemap by WordPress to Google using Google Search Console. The link to your sitemap is <a href="<?php echo get_home_url(); ?>/sitemap.xml"><?php echo get_home_url(); ?>/sitemap.xml</a>. Submit your sitemap to <a href="https://search.google.com">search.google.com</a>

<form method='post' name='redirect-<?php
class SchemaMarkupPlugin {
  // Existing code...

  public function __construct() {
    // Existing code...
    add_action('admin_menu', array($this, 'add_admin_menu'));
  }

  public function add_admin_menu() {
    add_submenu_page(
      'options-general.php',
      'Schema Markup Form',
      'Schema Markup Form',
      'manage_options',
      'schema-markup-form',
      array($this, 'render_schema_markup_form')
    );
  }

  public function render_schema_markup_form() {
    ?>
    <div class="wrap">
      <h1>Schema Markup Form</h1>
      <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
        <input type="hidden" name="action" value="submit_schema_markup_form">
        <?php wp_nonce_field('schema_markup_form'); ?>

        <label for="type">Type:</label>
        <input type="text" name="type" id="type" required>
        <p>Enter the type of organization or entity for Schema Markup (e.g., Organization, Nonprofit).</p>

        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>
        <p>Enter the name of your organization or entity.</p>

        <label for="logo">Logo:</label>
        <input type="url" name="logo" id="logo" required>
        <p>Enter the URL of your organization or entity's logo.</p>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>
        <p>Enter a description of your organization or entity.</p>

        <?php submit_button('Submit'); ?>
      </form>
    </div>
    <?php
  }
}

new SchemaMarkupPlugin();
?>
	<?php wp_nonce_field( $this->_redirectEditorSaveActionName, $this->_redirectEditorSaveActionNonceName ); ?>
	<p><textarea name='redirects' style='width:100%;height:15em;white-space:pre;font-family:Consolas,Monaco,monospace;'><?php print esc_textarea($redirects); ?></textarea></p>
	<p><button type='submit' name='function' class='button button-primary' value='redirect-editor-save'>Save redirect</button></p>
	<br/>
	<br/>
	<h2>Please support maintenance of this plugin!</h2>
	<form action="https://www.paypal.com/donate" method="post" target="_top">
		<input type="hidden" name="hosted_button_id" value="ZGH6WYRFJXMG8" />
		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
		<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
	</form>

	<p>
		Using the redirect feature is simple. You enter the URL of a page where something used to be and then the URL of where you want it to redirect, and it works.
		Make sure to write it using the relative domain name, like so http://www.example.com/2012/09/new-post/.
		Followed by the absolute URL of the destination separated by a space. Every redirect has to be on its own line. You can add comments using the # symbol.
		You can add comments by starting the line with #, and that line will be ignored.
	</p>
	<br/> 
	<p>
		Here is an example of a redirect:
		<pre><code>/2022/04/old-post/ http://www.example.com/

2022/01/new-post/</code></pre>
	</p>
	<br/>

