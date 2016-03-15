<?php
	/*
	* Helper class for mod_likebox
	*/ 
	
	class modLikeBoxHelper
	{
		public function showLikeBox()
		{
		?>
                    <div class="row hidden-phone hidden-tablet">
                        <div class="fb-page" data-href="https://www.facebook.com/AstroIsha" data-hide-cover="false" data-width="auto" data-show-facepile="true" data-show-posts="false">
                            <div class="fb-xfbml-parse-ignore">
                                <blockquote cite="https://www.facebook.com/AstroIsha">
                                    <a href="https://www.facebook.com/AstroIsha">AstroIsha</a>
                                </blockquote>
                            </div>
                        </div>
                        <div class="spacer"></div>
                        <!-- Place this tag where you want the widget to render. -->
                        <div class="g-page" data-href="//plus.google.com/u/0/100464003715258637571" data-showcoverphoto="false" data-rel="publisher"></div>
                        <div class="spacer"></div>
                        <a href="https://twitter.com/astroishaweb" class="twitter-follow-button" data-show-count="false" data-size="medium" data-dnt="true">Follow @rohandesai08</a>
                        <a class="twitter-timeline" href="https://twitter.com/astroishaweb" data-widget-id="709272586232975360">Tweets by @astroishaweb</a>
                        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                    </div>
		<?php
		}
	}
?>
