CREATE TABLE addressbooks (
    id INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    principaluri VARCHAR(255),
    displayname VARCHAR(255),
    uri VARCHAR(200),
    description TEXT,
    ctag INT(11) UNSIGNED NOT NULL DEFAULT '1',
    UNIQUE(principaluri, uri)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE cards (
    id INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    addressbookid INT(11) UNSIGNED NOT NULL,
    carddata MEDIUMBLOB,
    uri VARCHAR(200),
    lastmodified INT(11) UNSIGNED
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE calendarobjects (
    id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    calendardata MEDIUMBLOB,
    uri VARCHAR(200),
    calendarid INTEGER UNSIGNED NOT NULL,
    lastmodified INT(11) UNSIGNED,
    etag VARCHAR(32),
    size INT(11) UNSIGNED NOT NULL,
    componenttype VARCHAR(8),
    firstoccurence INT(11) UNSIGNED,
    lastoccurence INT(11) UNSIGNED,
    UNIQUE(calendarid, uri)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE calendars (
    id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    principaluri VARCHAR(100),
    displayname VARCHAR(100),
    uri VARCHAR(200),
    ctag INTEGER UNSIGNED NOT NULL DEFAULT '0',
    description TEXT,
    calendarorder INTEGER UNSIGNED NOT NULL DEFAULT '0',
    calendarcolor VARCHAR(10),
    timezone TEXT,
    components VARCHAR(20),
    transparent TINYINT(1) NOT NULL DEFAULT '0',
    UNIQUE(principaluri, uri)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
CREATE TABLE locks (
    id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    owner VARCHAR(100),
    timeout INTEGER UNSIGNED,
    created INTEGER,
    token VARCHAR(100),
    scope TINYINT,
    depth TINYINT,
    uri VARCHAR(1000),
    INDEX(token),
    INDEX(uri)
);

CREATE TABLE principals (
    id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    uri VARCHAR(200) NOT NULL,
    email VARCHAR(80),
    displayname VARCHAR(80),
    vcardurl VARCHAR(255),
    UNIQUE(uri)
);

CREATE TABLE groupmembers (
    id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    principal_id INTEGER UNSIGNED NOT NULL,
    member_id INTEGER UNSIGNED NOT NULL,
    UNIQUE(principal_id, member_id)
);


INSERT INTO principals (uri,email,displayname) VALUES
('principals/admin', 'admin@example.org','Administrator'),
('principals/admin/calendar-proxy-read', null, null),
('principals/admin/calendar-proxy-write', null, null);

CREATE TABLE users (
    id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50),
    digesta1 VARCHAR(32),
    UNIQUE(username)
);

INSERT INTO users (username,digesta1) VALUES
('admin',  '87fd274b7b6c01e48d7c2f965da8ddf7');
