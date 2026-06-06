CREATE OR REPLACE VIEW view_musics AS 
SELECT
    musics.ID AS music_id,
    musics.title AS music_title,
    musics.content AS music_content,
    musics.cover AS music_cover,
    musics.music_url AS music_url,
    musics.duration AS music_duration,
    musics.like_count AS music_like_count,
    musics.download_count AS music_download_count,
    musics.created_at AS music_created_at,
    artists.ID AS artist_id,    
    artists.full_name AS artist_name,
    artists.avatar AS artist_avatar,
    categories.title AS category_title,
    categories.ID AS category_id
FROM musics
JOIN music_artist ON musics.ID = music_artist.music_id
JOIN artists ON artists.ID = music_artist.artist_id
JOIN music_category ON musics.ID = music_category.music_id
JOIN categories ON categories.ID = music_category.category_id;



CREATE OR REPLACE VIEW view_comments AS SELECT
    users.ID AS user_id,
    users.name AS user_name,
    users.role AS user_role,
    users.avatar AS user_avatar,
    comments.ID AS comment_id,
    comments.comment AS comment_comment,
    comments.updated_at AS comment_updated_at,
    comments.status AS comment_status,
    comments.parent_id AS comment_parent_id,
    musics.ID AS music_id,
    musics.title AS music_title,
    musics.cover AS musics_cover
FROM
    comments
INNER JOIN users ON(users.ID = comments.user_id)
INNER JOIN musics ON(musics.ID = comments.music_id)