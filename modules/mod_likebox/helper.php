<?php
	/*
	* Helper class for mod_likebox
	*/ 
	
	class modLikeBoxHelper
	{
		public function showLikeBox()
		{
		?>
                    <table class="table-condensed">
                        <tr>
                            <td>
                                <div class="fb-like" data-href="http://www.astroisha.com" data-layout="box_count" data-action="like" data-colorscheme="dark" data-show-faces="false" data-share="false"></div>
                            </td>
                            <td>
                                <div class="g-plusone" data-size="tall" data-href="http://www.astroisha.com"></div>
                            </td>
                            <td>
                                <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.astroisha.com" data-count="vertical" data-size="medium" data-lang="en">Tweet</a>
                            </td>
                        </tr>
                    </table>
		<?php
		}
	}
?>
