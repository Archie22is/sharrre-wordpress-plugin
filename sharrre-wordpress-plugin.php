<?php
/*
Plugin Name: Sharrre WordPress Plugin
Plugin URI: http://www.paulund.co.uk
Description: A WordPress plugin that will allow you to create your own share buttons using the jQuery Plugin Sharrre
Version: 1.2
Author: Paul Underwood
Author URI: http:/www.paulund.co.uk
*/

/**
 * Copyright (c) `date "+%Y"` Your Name. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * **********************************************************************
 */
if(!is_admin())
{
	new Sharrre_Wordpress_plugin();
}

class Sharrre_Wordpress_Plugin
{
	private $buttons = array(
						'googlePlus',
						'facebook',
						'twitter',
						'digg',
						'delicious',
					    'stumbleupon',
					    'linkedin',
					    'pinterest'
						);

	private $googlePlusOptions = false;
	private $facebookOptions = false;
	private $twitterOptions = false;
	private $diggOptions = false;
	private $deliciousOptions = false;
	private $stumbleuponOptions = false;
	private $linkedinOptions = false;
	private $pinterestOptions = false;

	public function __construct()
	{
		add_action( 'wp_enqueue_scripts', array(&$this, 'add_scripts') );
		add_action( 'wp_enqueue_scripts', array(&$this, 'add_styles') );
	}

	public function add_scripts()
	{
		wp_enqueue_script( 'sharrre', plugins_url( 'sharrre/jquery.sharrre.min.js', __FILE__ ) , array('jquery') );
	}

	public function add_styles()
	{
		wp_enqueue_style( 'sharrre-button-style', plugins_url( 'css/styling.css', __FILE__ ) );
	}

	public function google_plus_options( $options )
	{
		$this->googlePlusOptions = json_encode($options);
	}

	public function facebook_button( $options )
	{
		$this->facebookOptions = json_encode($options);
	}

	public function twitter_button( $options )
	{
		$this->twitterOptions = json_encode($options);
	}

	public function digg_button( $options )
	{
		$this->diggOptions = json_encode($options);
	}

	public function delicious_button( $options )
	{
		$this->deliciousOptions = json_encode($options);
	}

	public function stumbleupon_button( $options )
	{
		$this->stumbleuponOptions = json_encode($options);
	}

	public function linkedin_button( $options )
	{
		$this->linkedinOptions = json_encode($options);
	}

	public function pinterest_button( $options )
	{
		$this->pinterestOptions = json_encode($options);
	}

    /**
     * Add the button to the page
     *
     * @param array  $buttons - Array of buttons you want to display
     * @param String $div_id  - If the div id is null then we create a new div. If the div id is not null
     *                          then we assume that a div has been created for the buttons
     * @param String $url     - The url you want to share, if NULL then we get the current post URL
     */
	public function add_button(array $buttons, $div_id = NULL, $url = NULL, $options = array())
	{
        $optionsDefaults = array('enableHover' => true, 'enableCounter' => true, 'enableTracking' => false);

        $buttonOptions = wp_parse_args( $options, $optionsDefaults );

        if(is_null($url))
        {
            $currentPostUrl = get_permalink();

            if(!empty($currentPostUrl))
            {
                $url = $currentPostUrl;
            }
        }

        if(is_null($div_id))
        {
            $div_id = 'sharrre_button';
            printf('<div id="%s"></div>', $div_id);
        }
		?>
			<script>
				jQuery('#<?php echo $div_id; ?>').sharrre({

				<?php
					if(!empty($buttons))
					{
						echo 'share: {';

						foreach($buttons as $button)
						{
							echo $button . ': true,';
						}

						echo '},';
					}

					echo 'buttons: {';
						if($this->googlePlusOptions)
						{
							echo 'googlePlus: ' . $this->googlePlusOptions . ',';
						}
						if($this->facebookOptions)
						{
							echo 'facebook: ' . $this->facebookOptions . ',';
						}
						if($this->twitterOptions)
						{
							echo 'twitter: ' . $this->twitterOptions . ',';
						}
						if($this->diggOptions)
						{
							echo 'digg: ' . $this->diggOptions . ',';
						}
						if($this->deliciousOptions)
						{
							echo 'delicious: ' . $this->deliciousOptions . ',';
						}
						if($this->stumbleuponOptions)
						{
							echo 'stumbleupon: ' . $this->stumbleuponOptions . ',';
						}
						if($this->linkedinOptions)
						{
							echo 'linkedin: ' . $this->linkedinOptions . ',';
						}
						if($this->pinterestOptions)
						{
							echo 'pinterest: ' . $this->pinterestOptions . ',';
						}

					echo '},';

                    if(!$buttonOptions['enableHover'])
                    {
                        echo 'enableHover: false,';
                    }

                    if(!$buttonOptions['enableCounter'])
                    {
                        echo 'enableCounter: false,';
                    }

                    if($buttonOptions['enableTracking'])
                    {
                        echo 'enableTracking: true,';
                    }

					if(!empty($url))
					{
						printf("url: '%s',", $url);
					}
				?>
				});
			</script>
		<?php
	}
}

?>