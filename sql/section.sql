
CREATE TABLE `sectioninfo` (
 `SECTION_ID` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
 `SECTION_NAME` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
 `SECTION_DESC` varchar(100) COLLATE utf8_unicode_ci NULL,
 `PUBLIC_YN` varchar(1) COLLATE utf8_unicode_ci NULL,
  PRIMARY KEY (SECTION_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


insert into sectioninfo values("1000","It's one", "one에 대한 이것저것","Y");
insert into sectioninfo values("2000","It's two", "two에 대한 이것저것","Y");
insert into sectioninfo values("3000","It's three", "three에 대한 이것저것","Y");
insert into sectioninfo values("9000","It's bomb", "bomb에 대한 비밀","N");
CREATE TABLE `sectioninfo` (
 `SECTION_ID` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
 `SECTION_NAME` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
 `SECTION_DESC` varchar(100) COLLATE utf8_unicode_ci NULL,
 `PUBLIC_YN` varchar(1) COLLATE utf8_unicode_ci NULL,
  PRIMARY KEY (SECTION_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


insert into sectioninfo values("1000","It's one", "one에 대한 이것저것","Y");
insert into sectioninfo values("2000","It's two", "two에 대한 이것저것","Y");
insert into sectioninfo values("3000","It's three", "three에 대한 이것저것","Y");
insert into sectioninfo values("9000","It's bomb", "bomb에 대한 비밀","N");
