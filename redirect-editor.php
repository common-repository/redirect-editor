<?php
/*
Plugin Name: SEO & Redirect
Version: 3.1.7
Plugin URI: https://planetzuda.com
Description:  SEO is king, and content is queen. That's why we offer a comprehensive range of solutions to help you maximize your online visibility and drive targeted traffic to your website. From redirecting links to avoid 404 error pages to creating unique SEO-optimized links, our services are designed to enhance your website's performance and attract the attention of search engines. With our expertise, you can take your SEO strategy to new heights and stay ahead of the competition.

Tired of losing potential customers due to broken links? Our link redirection feature ensures that every visitor lands on the right page, eliminating frustrating 404 error pages. By seamlessly redirecting links, we enhance the user experience by adding in text tags for images taken from your caption or post automatically, which is an attempt in automation to comply with the ADA, which a lot of sites legally have to comply with, however the best way to comply is to fill in the alt tag yourself. We keep visitors engaged with your content. Plus, search engines love websites that are well-maintained and error-free, which can boost your search engine rankings. With our service, you can ensure that your website provides a seamless browsing experience, leading to higher customer satisfaction and increased conversions.

Don't let your website get lost in the vast ocean of online content. Our unique and SEO-optimized links are designed to make your website stand out from the crowd. We carefully craft keyword-rich links that align with your target audience's search queries, helping search engines recognize the relevance and authority of your pages. By leveraging our link optimization techniques, you can significantly improve your search engine visibility, attract organic traffic, and drive qualified leads to your website. Stay one step ahead of your competitors with our powerful link optimization solutions.

Enhancing your website's SEO goes beyond just optimizing links. With our service, you can take control of your website's metadata and make it more appealing to search engines. Add descriptions, keywords, and custom titles to your web pages, effectively communicating their content and purpose to search engines. Additionally, we automatically optimize your image alt tags by grabbing the caption or using the post title if the alt tag is empty. By strategically incorporating relevant keywords in alt tags, you can increase your chances of ranking higher in search results, attracting more organic traffic, and reaching your target audience. Our XML sitemap creation feature ensures that search engines can efficiently crawl and index your website, further boosting your online visibility and driving sustained organic traffic.

Experience the difference our comprehensive SEO optimization services can make for your website. From redirecting links to crafting unique SEO-optimized content, we have the tools and expertise to propel your online presence to new heights. Don't let your competitors steal the spotlight—join us and let your website shine in the vast digital landscape. Contact us today to get started on your journey to SEO success.
Author: Planet Zuda
Author URI: https://planetzuda.com
LICENSE
Copyright 2012-2016 Justin Watt
 Copyright 2018-present Planet Zuda  sales@planetzuda.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
class Redirect_Editor_Plugin {

    const NOTICES_OPTION_KEY = 'airtight_notices';

    // UI components names
    public $_redirectEditorSaveActionNonceName = "redirect-editor-nonce";
    public $_redirectEditorSaveActionName = "redirect-editor";
    public $_redirectEditorSaveActionFunctionName = "redirect-editor-save";
    public $_redirectEditorActivateActionNonceName = "redirect-editor-activate-nonce";
    public $_redirectEditorActivateActionName = "redirect-editor-activate";

    public function __construct() {
        add_action('admin_init', array($this, 'save_data'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('init', array($this, 'PZ_security_protection'));
        add_action('init', array($this, 'css_style'));
        add_action('admin_notices', array($this, 'output_notices'));

        // Yoast fix
        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            header_register_callback(function () {
                $request_uri = esc_url($_SERVER['REQUEST_URI']);
                if (strpos($request_uri, '.xml') !== false) {
                    foreach (headers_list() as $header) {
                        if (strpos($header, 'X-Robots-Tag:') !== false) {
                            header_remove('X-Robots-Tag');
                        }
                    }
                }
            });
        }
    }

    
 public function meta_tags()
{
get_meta_tags();
}


    /* currently only allowing text, but will be adding in more support in the future. */

    public function PZ_security_protection()
    {
    
     
  add_action('pre_get_posts', array(
            $this,
            'redirect'
        ));

              }
}
			class BackendAdminPage {
    public function __construct() {
        add_action('admin_menu', array($this, 'register_admin_page'));
        add_action('admin_init', array($this, 'save_redirects'));
    }

    public function css_style() {
        wp_register_style('style', get_stylesheet_uri());
        wp_enqueue_style('style');
    }

    public function render_admin_page() {
        $settings = $this->get_saved_settings();
        $redirects_raw = isset($settings['redirects_raw']) ? $settings['redirects_raw'] : '';
        $redirects = isset($settings['redirects']) ? $settings['redirects'] : array();
        ?>
        <div class="wrap">
            <h1>Redirect Editor</h1>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php
                wp_nonce_field('redirect-editor-save', 'redirect_editor_nonce');
                ?>
                <input type="hidden" name="action" value="save_redirects">
                <textarea name="redirects_raw"  rows="30" cols="200"><?php echo esc_textarea($redirects_raw); ?></textarea>
                <br/>
                <input type="submit" class="button-primary" value="Save Redirects">
            </form>
        </div>
        <?php
    }

    public function register_admin_page() {
        add_menu_page(
            'SEO Plugin',
            'SEO Plugin',
            'manage_options',
            'seo-plugin-settings',
            array($this, 'render_admin_page'),
            'dashicons-search',
            10
        );
    }

    public function get_saved_settings() {
        $settings = get_option('redirect_editor', array());

        if (!is_array($settings)) {
            $settings = array();
        }

        return $settings;
    }

    public function save_redirects() {
        if (
            isset($_POST['redirect_editor_nonce']) &&
            wp_verify_nonce($_POST['redirect_editor_nonce'], 'redirect-editor-save') &&
            current_user_can('manage_options') &&
            isset($_POST['redirects_raw'])
        ) {
            $redirects_raw = sanitize_textarea_field($_POST['redirects_raw']);
            $redirects = array();

            // Process and sanitize the raw redirects data as needed

            // Save the redirects to the database or perform any other desired action
            $settings = $this->get_saved_settings();
            $settings['redirects_raw'] = $redirects_raw;
            $settings['redirects'] = $redirects;
            update_option('redirect_editor', $settings);

            // Redirect back to the admin page with a success message
            wp_safe_redirect(add_query_arg('notice', urlencode('Redirects saved!'), admin_url('admin.php?page=seo-plugin-settings')));
            exit;
        }
    }
}

new BackendAdminPage();

class Custom_Page_Title {
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_custom_meta_box'));
        add_action('save_post', array($this, 'save_custom_page_title'));
    }

    public function add_custom_meta_box() {
        // Add the meta box to the post editing screen
        add_meta_box(
            'custom-page-title-meta-box',
            'Custom Page Title',
            array($this, 'render_custom_meta_box'),
            'post',
            'side',
            'high'
        );
    }

    public function render_custom_meta_box($post) {
        // Retrieve the custom page title for the post
        $custom_page_title = get_post_meta($post->ID, '_custom_page_title', true);

        // Output the HTML for the meta box
        ?>
        <input type="text" name="custom_page_title" value="<?php echo esc_attr($custom_page_title); ?>" style="width:100%;" placeholder="Enter custom page title">
        <?php
    }

    public function save_custom_page_title($post_id) {
        // Check if the custom page title field is present in the $_POST data
        if (isset($_POST['custom_page_title'])) {
            // Sanitize the input and save the custom page title as post meta
            $custom_page_title = sanitize_text_field($_POST['custom_page_title']);
            update_post_meta($post_id, '_custom_page_title', $custom_page_title);
        }
    }
}

new Custom_Page_Title();

class Custom_SEO_Description {
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_custom_meta_boxes'));
        add_action('save_post', array($this, 'save_custom_seo_data'));
        add_action('wp_head', array($this, 'output_meta_data'));
    }

    public function add_custom_meta_boxes() {
        // Add the meta boxes to the post and page editing screens
        add_meta_box(
            'custom-seo-description-meta-box',
            'Custom SEO Description',
            array($this, 'render_custom_seo_description_meta_box'),
            array('post', 'page'),
            'normal',
            'high'
        );

        add_meta_box(
            'custom-seo-keywords-meta-box',
            'Custom SEO Keywords',
            array($this, 'render_custom_seo_keywords_meta_box'),
            array('post', 'page'),
            'normal',
            'high'
        );
    }

    public function render_custom_seo_description_meta_box($post) {
        // Retrieve the custom SEO description for the post
        $custom_seo_description = get_post_meta($post->ID, '_custom_seo_description', true);

        // Output the HTML for the meta box
        ?>
        <textarea name="custom_seo_description" rows="3" style="width:100%;"><?php echo esc_textarea($custom_seo_description); ?></textarea>
        <?php
    }

    public function render_custom_seo_keywords_meta_box($post) {
        // Retrieve the custom SEO keywords for the post
        $custom_seo_keywords = get_post_meta($post->ID, '_custom_seo_keywords', true);

        // Output the HTML for the meta box
        ?>
        <input type="text" name="custom_seo_keywords" value="<?php echo esc_attr($custom_seo_keywords); ?>" style="width:100%;">
        <?php
    }

    public function save_custom_seo_data($post_id) {
        // Check if the custom SEO description field is present in the $_POST data
        if (isset($_POST['custom_seo_description'])) {
            // Sanitize the input and save the custom SEO description as post meta
            $custom_seo_description = sanitize_textarea_field($_POST['custom_seo_description']);
            update_post_meta($post_id, '_custom_seo_description', $custom_seo_description);
        }

        // Check if the custom SEO keywords field is present in the $_POST data
        if (isset($_POST['custom_seo_keywords'])) {
            // Sanitize the input and save the custom SEO keywords as post meta
            $custom_seo_keywords = sanitize_text_field($_POST['custom_seo_keywords']);
            update_post_meta($post_id, '_custom_seo_keywords', $custom_seo_keywords);
        }
    }

    public function output_meta_data() {
        // Get the current post or page object
        $post = get_queried_object();

        // Check if the post or page has a custom SEO description
        $custom_seo_description = get_post_meta($post->ID, '_custom_seo_description', true);
        if (!empty($custom_seo_description)) {
            // Output the custom SEO description as the meta description tag
            echo '<meta name="description" content="' . esc_attr($custom_seo_description) . '">' . "\n";
        }

        // Check if the post or page has custom SEO keywords
        $custom_seo_keywords = get_post_meta($post->ID, '_custom_seo_keywords', true);
        if (!empty($custom_seo_keywords)) {
            // Output the custom SEO keywords as meta tags
            echo '<meta name="keywords" content="' . esc_attr($custom_seo_keywords) . '">' . "\n";
        }
    }
}

new Custom_SEO_Description();
class UniqueSeoSlugGenerator {
    public static function generate($slug, $post_id, $post_type) {
        $suffix = 2; // Initial suffix
        $unique_slug = $slug;

        // Check if the slug exists for any other published posts or pages
        while (true) {
            $args = array(
                'name'        => $unique_slug,
                'post_type'   => $post_type,
                'post_status' => array('publish', 'pending', 'draft', 'future', 'private'),
                'numberposts' => 1,
                'exclude'     => array($post_id)
            );

            $posts = get_posts($args);

            if (empty($posts)) {
                break; // Unique slug found, exit the loop
            }

            $unique_slug = $slug . '-' . $suffix;
            $suffix++;
        }

        return $unique_slug;
    }
}



//$unique_slug = UniqueSeoSlugGenerator::generate($slug, $post_id, $post_type);
//echo $unique_slug; // Output: "example-post-slug-2" (if the initial slug exists)

/**
 * Function to get the post title or image caption for alt tag content.
 *
 * @param int $post_id The ID of the post.
 * @return string|bool Alt tag content on success, false on failure.
 */
function get_post_title_or_image_caption($post_id) {
    $post_title = get_the_title($post_id);
    $image_caption = get_the_post_thumbnail_caption($post_id);

    if (!empty($image_caption)) {
        // Limit caption to 140 characters.
        $caption = substr($image_caption, 0, 140);

        // Remove HTML tags and extra spaces from the caption.
        $caption = wp_strip_all_tags($caption);
        $caption = trim($caption);

        // Remove stop words from the caption.
        $stop_words = array(
            'a', 'an', 'the', 'and', 'but', 'or', 'nor', 'for', 'so', 'yet', 'not',
            'in', 'on', 'at', 'to', 'from', 'by', 'with', 'about', 'above', 'below',
            'over', 'under', 'among', 'between', 'through', 'during', 'before', 'after',
            'up', 'down', 'out', 'off', 'on', 'in', 'into', 'onto', 'after', 'before',
            'behind', 'above', 'below', 'under', 'over', 'between', 'among', 'throughout',
            'underneath', 'beside', 'around', 'amongst', 'upon'
        ); // Add more stop words as needed.

        $caption_words = preg_split("/[\s,]+/", $caption);
        $filtered_words = array_diff($caption_words, $stop_words);

        // Define an exhaustive list of action, expressive, and descriptive words.
        $action_words = array(
            'accomplish', 'achieve', 'act', 'animate', 'apply', 'arrange', 'ascend', 'assemble',
            'assert', 'assess', 'assign', 'attain', 'awaken', 'balance', 'breathe', 'build',
            'calculate', 'capture', 'celebrate', 'challenge', 'change', 'characterize', 'choose',
            'collaborate', 'combine', 'command', 'communicate', 'complete', 'compose', 'concentrate',
            'conclude', 'connect', 'conquer', 'construct', 'contribute', 'control', 'coordinate',
            'create', 'cultivate', 'decide', 'dedicate', 'define', 'deliver', 'design', 'develop',
            'discover', 'display', 'diversify', 'do', 'document', 'draw', 'drive', 'earn',
            'educate', 'elevate', 'embrace', 'emerge', 'encourage', 'enhance', 'enjoy', 'enlighten',
            'enrich', 'envision', 'establish', 'evaluate', 'evolve', 'examine', 'excel', 'execute',
            'explore', 'express', 'extend', 'extract', 'facilitate', 'foster', 'generate', 'give',
            'guide', 'harmonize', 'harness', 'highlight', 'identify', 'ignite', 'illustrate', 'implement',
            'improve', 'incorporate', 'increase', 'influence', 'initiate', 'innovate', 'inspect', 'inspire',
            'integrate', 'interact', 'interpret', 'introduce', 'investigate', 'invigorate', 'join', 'journey',
            'justify', 'launch', 'lead', 'learn', 'listen', 'manage', 'maximize', 'measure', 'merge',
            'motivate', 'navigate', 'observe', 'operate', 'organize', 'overcome', 'participate', 'perform',
            'persuade', 'plan', 'play', 'practice', 'prepare', 'present', 'produce', 'promote', 'propose',
            'protect', 'provide', 'pursue', 'question', 'realize', 'reconcile', 'record', 'recruit', 'reflect',
            'reinforce', 'relate', 'release', 'remodel', 'renovate', 'research', 'resolve', 'revitalize',
            'revive', 'reward', 'satisfy', 'schedule', 'share', 'show', 'simplify', 'solve', 'spark',
            'succeed', 'support', 'surpass', 'synthesize', 'take', 'teach', 'test', 'transform', 'travel',
            'uncover', 'unite', 'utilize', 'validate', 'value', 'visualize', 'win', 'witness'
        );

        $expressive_words = array(
            'adventurous', 'amazing', 'awe-inspiring', 'beautiful', 'breathtaking', 'brilliant',
            'captivating', 'charming', 'colorful', 'creative', 'delightful', 'dramatic', 'dynamic',
            'elegant', 'enchanting', 'energetic', 'enthralling', 'entertaining', 'exhilarating',
            'extraordinary', 'fascinating', 'fun', 'gorgeous', 'impressive', 'innovative', 'inspiring',
            'intriguing', 'joyful', 'lively', 'lovely', 'magical', 'mesmerizing', 'mind-blowing', 'mysterious',
            'outstanding', 'passionate', 'powerful', 'remarkable', 'sensational', 'spectacular', 'stunning',
            'surprising', 'thrilling', 'vibrant', 'wonderful'
        );

        $descriptive_words = array(
            'ancient', 'beautiful', 'charismatic', 'cozy', 'elegant', 'enchanting', 'exotic',
            'fascinating', 'fragrant', 'grand', 'historic', 'majestic', 'magnificent', 'mystical',
            'peaceful', 'picturesque', 'quaint', 'rustic', 'scenic', 'serene', 'soothing', 'splendid',
            'tranquil', 'unique', 'vibrant', 'vivid'
        );

        $valuable_words = array();
        foreach ($filtered_words as $word) {
            if (in_array($word, $action_words) || in_array($word, $expressive_words) || in_array($word, $descriptive_words)) {
                $valuable_words[] = $word;
            }
        }

        // Use the valuable words to create the alt tag content.
        $alt_content = implode(' ', $valuable_words);

        if (!empty($alt_content)) {
            return $alt_content;
        }
    }

    // If no valuable words are found or caption is empty, return the post title.
    if (!empty($post_title)) {
        return $post_title;
    }

    return false;
}



?>

