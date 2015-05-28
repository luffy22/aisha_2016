<?php
	/*
	* Helper class for mod_likebox
	*/ 
	
	class modLikeBoxHelper
	{
            public function showLikeBox()
            {
            ?>
                <div id="fb-root"></div>
                <script>(function(d, s, id) {
                  var js, fjs = d.getElementsByTagName(s)[0];
                  if (d.getElementById(id)) return;
                  js = d.createElement(s); js.id = id;
                  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3&appId=220390744824296";
                  fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));</script>

                    <div class="fb-page" data-href="https://www.facebook.com/AstroIsha" data-hide-cover="false" data-width="auto" data-show-facepile="true" data-show-posts="false">
                        <div class="fb-xfbml-parse-ignore">
                            <blockquote cite="https://www.facebook.com/AstroIsha">
                                <a href="https://www.facebook.com/AstroIsha">AstroIsha</a>
                            </blockquote>
                        </div>
                    </div>
            <?php
            }
	}
?>
