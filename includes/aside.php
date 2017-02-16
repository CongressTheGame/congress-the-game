<aside>
<?php  if(logged_in() === true){
	 include 'includes/widgets/loggedin.php';
	 if(has_access($_SESSION['user_id'],1)===true){
	 include 'includes/widgets/user_count.php';
	 }
 }else{
	 include 'includes/widgets/login.php';
 }
 ?>
 <br>
 <div align="center">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- AsideAuto -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-7715624314417434"
     data-ad-slot="5290830347"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</div>

</aside>