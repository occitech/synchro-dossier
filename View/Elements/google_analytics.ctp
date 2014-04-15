<?php
/**
 * Google Analytics element
 * Allows to include the Google Analytics code configured in the admin backend
 * If Google Analytics has not be enabled, the tracking code will not be displayed
 *
 * The tracking code is not displayed in debug mode by default, the option can
 * be changed from the backend
 */
$gaCode = Configure::read('Analytics.code');
$gaDomain = Configure::read('Analytics.domain');
if (!empty($gaCode) && !empty($gaDomain) && Configure::read('debug') == 0) :
?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '<?php echo $gaCode; ?>', '<?php echo $gaDomain; ?>');
  ga('require', 'displayfeatures');
  ga('send', 'pageview');

</script>
<?php endif; ?>