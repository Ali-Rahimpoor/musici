<?php
/** 
 * @var array $music
*/
?>
<a href='<?php echo site_url('?artist='.$music['artist_id']); ?>' class="artist-card-mini">
   <img src="<?php echo site_url($music['artist_avatar']) ?>" alt="Artist" class="artist-img-mini">
   <span  class="artist-name-mini"><?php echo $music['artist_name']; ?></span>
</a>
