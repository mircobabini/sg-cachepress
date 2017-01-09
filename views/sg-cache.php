<div class="sgwrap">
	<div class="box">
		<h2><?php _e( 'SuperCacher for WordPress by SiteGround', 'sg-cachepress' ) ?></h2>		
		<p><?php _e( 'The SuperCacher is a system that allows you to use the SiteGround dynamic cache and Memcached to optimize the performance of your WordPress. In order to take advantage of the system you should have the SuperCacher enabled at your web host plus the required cache options turned on below. For more information on the different caching options refer to the <a href="http://www.siteground.com/tutorials/supercacher/" target="_blank">SuperCacher Tutorial</a>!', 'sg-cachepress' ) ?></p>
	</div>    
    
	<div class="box sgclr">
            <h2><?php _e( 'PHP Version Status', 'sg-cachepress' ) ?></h2>

            <div class="greybox">
                    <p><?php _e( 'Checks the PHP version your WordPress site is '
                            . 'running and whether you\'re on the fastest possible PHP version.', 'sg-cachepress' ) ?></p>
<!--
                    <form method="post" action="<?php menu_page_url( 'sg-cachepress-phpversion-check' ); ?>">                            
                        <?php submit_button( __( 'Check PHP Version', 'sg-cachepress' ), 'primary', 'sg-cachepress-phpversion-check', false );?>
                    </form>-->
            </div>

            <!-- START -->                 
            <?php
            $phpversions = apply_filters('phpcompat_phpversions', array(
                'PHP 7.0' => '7.0',
                'PHP 5.6' => '5.6',
            ));

            $test_version = '7.0';
            $only_active = 'yes';
            ?>
            <table class="form-table" style="display:none;">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label for="phptest_version">
                                <?php _e('PHP Version', 'sg-cachepress'); ?>
                            </label>
                        </th>
                        <td>
                            <?php
                            foreach ($phpversions as $name => $version) {
                                printf('<label><input type="radio" name="phptest_version" value="%s" %s /> %s</label><br>', $version, checked($test_version, $version, false), $name);
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="active_plugins"><?php _e('Only Active', 'sg-cachepress'); ?>
                            </label>
                        </th>
                        <td>
                            <label>
                                <input type="radio" name="active_plugins" value="yes" <?php checked($only_active, 'yes', true); ?> />
                                <?php _e('Only scan active plugins and themes', 'sg-cachepress'); ?>
                            </label>
                            <label>
                                <input type="radio" name="active_plugins" value="no" <?php checked($only_active, 'no', true); ?> />
                                <?php _e('Scan all plugins and themes', 'sg-cachepress'); ?>
                            </label>
                        </td>
                    </tr>
                </tbody>
            </table>

            <p>
            <div style="display: none;" id="wpe-progress">
                <label for=""><?php _e('Progress', 'sg-cachepress'); ?></label>
                <div id="progressbar"></div>
                <div id="wpe-progress-count"></div>
                <div id="wpe-progress-active"></div>
            </div>

            <!-- Area for pretty results. -->
            <div id="standardMode"></div>

            <!-- Area for developer results. -->
            <div style="display: none;" id="developerMode">
                <b><?php _e('Test Results:', 'sg-cachepress'); ?></b>
                <textarea readonly="readonly" id="testResults"></textarea>
            </div>

            <div id="footer" style="display: none;">
                <?php /*
                _e('Note: PHP Warnings will not cause errors, '
                        . 'but could cause compatibility issues with future PHP versions, '
                        . 'and could spam your PHP logs.', 'sg-cachepress');
                */?><br>
                <a id="downloadReport" href="#"><?php _e('Download Report', 'sg-cachepress'); ?></a>
            </div>
            </p>
            <p>
            <input style="float: left;" name="run" id="runButton" type="button" 
                       value="<?php _e('Check PHP Version', 'sg-cachepress'); ?>" 
                       class="button-primary" />
            
            <div class="wpe-tooltip">
                <input style="height: 40px; line-height: 40px; text-align: center; margin-left: 5px;" 
                       name="run" id="cleanupButton" type="button" 
                       value="<?php _e('Clean up', 'sg-cachepress'); ?>" class="button" />
                <span class="wpe-tooltiptext"> <br /> <br />
                    This will remove all database options related to this plugin, 
                    but it will not stop a scan in progress. 
                </span>
            </div>
<!--            <div style="display:none; visibility: visible; float: left;" class="spinner"></div>-->
            </p>

            <!-- Results template -->
            <script id="result-template" type="text/x-handlebars-template">
                    <div style="border-left-color: {{#if skipped}}#999999{{else if passed}}#49587c{{else}}#e74c3c{{/if}};" class="wpe-results-card">
                            <div class="inner-left">
                {{#if skipped}}<img src="<?php echo esc_url(plugins_url('../php-compatibility-checker/src/images/question.png', __FILE__)); ?>">{{else if passed}}<img src="<?php echo esc_url(plugins_url('../php-compatibility-checker/src/images/check.png', __FILE__)); ?>">{{else}}<img src="<?php echo esc_url(plugins_url('../php-compatibility-checker/src/images/x.png', __FILE__)); ?>">{{/if}}
                            </div>
                            <div class="inner-right">
                                    <h3 style="margin: 0px;">{{plugin_name}}</h3>
                                    {{#if skipped}}<?php _e('Unknown', 'sg-cachepress'); ?>{{else if passed}}PHP {{test_version}} <?php _e('compatible', 'sg-cachepress'); ?>.{{else}}<b><?php _e('Possibly not', 'sg-cachepress'); ?></b> PHP {{test_version}} <?php _e('compatible', 'sg-cachepress'); ?>.{{/if}}<br>
                                    {{update}}<br>
                <textarea style="display: none; white-space: pre;">{{logs}}</textarea><a class="view-details"><?php _e('view details', 'sg-cachepress'); ?></a>
            </div>
            <?php $update_url = site_url('wp-admin/update-core.php', 'admin'); ?>
                                    <div style="float:right;">{{#if updateAvailable}}<div class="badge wpe-update"><a href="<?php echo esc_url($update_url); ?>"><?php _e('Update Available', 'sg-cachepress'); ?></a></div>{{/if}}{{#if warnings}}<div class="badge warnings">{{warnings}} <?php _e('Warnings', 'sg-cachepress'); ?></div>{{/if}}{{#if errors}}<div class="badge errors">{{errors}} <?php _e('Errors', 'sg-cachepress'); ?></div>{{/if}}</div>
                            </div>
            </script>                
            <!-- END -->

	</div>
            
    	<div class="box sgclr">
		<h2><?php _e( 'Dynamic Cache Settings', 'sg-cachepress' ) ?></h2>
	
		<div class="three sgclr">
			<div class="greybox">
				<h3><?php _e( 'Dynamic Cache', 'sg-cachepress' ) ?></h3>
				<a href="" id="sg-cachepress-dynamic-cache-toggle" class="<?php  if ( $this->options_handler->get_option('enable_cache') ==1 ) echo 'toggleon'; else echo 'toggleoff'; ?>"></a>
				<p id="sg-cachepress-dynamic-cache-text"><?php _e( 'Enable the Dynamic caching system', 'sg-cachepress' ) ?></p>
				<p id="sg-cachepress-dynamic-cache-error" class="error"></p>
			</div>
		
			<div class="greybox">
				<h3><?php _e( 'AutoFlush Cache', 'sg-cachepress' ) ?></h3>
				<a href="" id="sg-cachepress-autoflush-cache-toggle" class="<?php  if ( $this->options_handler->get_option('autoflush_cache') ==1 ) echo 'toggleon'; else echo 'toggleoff'; ?>"></a>
				<p id="nginxcacheoptimizer-autoflush-cache-text"><?php _e( 'Automatically flush the Dynamic cache when you edit your content.', 'sg-cachepress' ) ?></p>
				<p id="nginxcacheoptimizer-autoflush-cache-error" class="error"></p>
			</div>
		
			<div class="greybox">
				<h3><?php _e( 'Purge Cache', 'sg-cachepress' ) ?></h3>
				<form class="purgebtn" method="post" action="<?php menu_page_url( 'sg-cachepress-purge' ); ?>">
					<?php submit_button( __( 'Purge the Cache', 'sg-cachepress' ), '', 'sg-cachepress-purge', false );?>
				</form>
				<p><?php _e( 'Purge all the data cached by the Dynamic cache.', 'sg-cachepress' ) ?></p>
			</div>
			
		</div>
		<div class="greybox">
			<h3><?php _e( 'Exclude URLs From Dynamic Caching', 'sg-cachepress' ) ?></h3>
			<p><?php _e( 'Provide a list of your website URLs you would like to exclude from the cache. For example if you would like to exclude: <strong>http://domain.com/path/to/url</strong><br>
			You can simply input the "path" string part of the URL. Then each URL that consists of it will be excluded. Divide each URL by a new line.', 'sg-cachepress' ) ?></p>
			
			<form method="post" action="<?php menu_page_url( 'sg-cachepress-purge' ); ?>">
				<textarea id="sg-cachepress-blacklist-textarea"><?php  echo esc_textarea($this->options_handler->get_blacklist()); ?></textarea>
				<?php submit_button( __( 'Update the Exclude List', 'sg-cachepress' ), 'primary', 'sg-cachepress-blacklist', false );?>
			</form>
		</div>
	</div>                                      
	<div class="box">
		<h2><?php _e( 'Memcached Settings', 'sg-cachepress' ) ?></h2>
		<div class="greybox">
				
			<a href="" id="sg-cachepress-memcached-toggle" class="<?php  if ( $this->options_handler->get_option('enable_memcached') ==1 ) echo 'toggleon'; else echo 'toggleoff'; ?>"></a>
			
			<p id="sg-cachepress-memcached-text"><?php _e( 'Enable Memcached', 'sg-cachepress' ) ?></p>
			<p class="error" id="sg-cachepress-memcached-error"></p>
				
			<div class="clr"></div>
			<p><?php _e( 'Store in the server\'s memory frequently executed queries to the database for a faster access on a later use.', 'sg-cachepress' ) ?></p>
			<div class="clr"></div>		
		</div>
	</div>
	
	<div class="box sgclr">
		<h2><?php _e( 'Dynamic Cache Status', 'sg-cachepress' ) ?></h2>
		<div class="greybox">
			
			<form class="purgebtn" method="post" action="<?php menu_page_url( 'sg-cachepress-test' ); ?>" id="cachetest">
				<?php echo get_home_url()?>/&nbsp;<input id="testurl" type="" name="" value="" />
				<?php submit_button( __( 'Test URL', 'sg-cachepress' ), 'primary', 'sg-cachepress-test', false );?>
			</form>
			
			<div class="status_test" style="display:none;"><?php _e( 'Status:', 'sg-cachepress' ) ?> <span id="status_test_value"></span></div>
				
			<div class="clr"></div>
			<p><?php _e( 'Check if this URL is dynamic or cached. Leave empty for your index or <strong>/example/</strong> for another page.', 'sg-cachepress' ) ?></p>
			<div class="clr"></div>		
		</div>
	</div>
</div>