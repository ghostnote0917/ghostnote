CREATE TABLE `contents` (
 `SN` int(11)  NOT NULL AUTO_INCREMENT,
 `SECTION_ID` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
 `CONTENT_TYPE` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
 `CONTENT_ID` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
 `TITLE` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 `DESCRIPTION` blob NOT NULL,
 `TAGS` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 `PRIVATE_YN` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
 `THUMBNAIL_URL` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 `VIDEO_PATH` varchar(255) COLLATE utf8_unicode_ci null,
 `STATUS` varchar(1) NOT NULL,
 `CREATED` date,
 PRIMARY KEY (SN)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

alter table contents add index CONTENTS_INDEX_U01(SECTION_ID, CONTENT_TYPE, CONTENT_ID);
