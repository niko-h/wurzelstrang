DROP TABLE IF EXISTS siteinfo;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS site_admins;
DROP TABLE IF EXISTS sites;


CREATE TABLE IF NOT EXISTS siteinfo(
	site_language TEXT,
	site_title    TEXT,
	site_theme    TEXT,
	site_headline TEXT,
	site_levels   BOOLEAN
);

	CREATE TABLE IF NOT EXISTS users(
	id            INTEGER PRIMARY KEY AUTOINCREMENT,
	user_email    TEXT,
	admin         BOOLEAN,
	CONSTRAINT users_unique UNIQUE (user_email)
);

	CREATE TABLE IF NOT EXISTS site_admins(
	user_id       INTEGER,
	site_id       INTEGER,
	CONSTRAINT site_admin_unique UNIQUE (user_id, site_id)
);

	CREATE TABLE IF NOT EXISTS sites(
	id        INTEGER PRIMARY KEY AUTOINCREMENT,
	language  TEXT,
	title     INTEGER,
	mtime     INTEGER,
	content   TEXT,
	template  TEXT,
	pos       INTEGER,
	visible   BOOLEAN,
	level     INTEGER
);

INSERT INTO siteinfo(site_language site_title, site_theme, site_headline, site_levels) VALUES ("de", "Wurzelstrang Demo", "Standart", "Wurzelstrang", 0);

INSERT INTO users(user_email, admin) VALUES ("post@bombenlabor.de", 1);

INSERT INTO site_admins(user_id, site_id) VALUES (1, 3);

INSERT INTO sites(language, title, content, pos, visible) VALUES ("de", "inhalt eins", "Skate ipsum dolor sit amet, Chris Buchinsky noseblunt slide 900 betty frigid air gap wall ride flail. 50-50 crooked grind hardware steps tail shinner Vatoland birdie. Sketchy Saran Wrap shinner hand rail bank backside rad. Hang-up helipop sketchy wax hip ho-ho face plant. Carve mongo dude John Lucero ollie hole skate or die grab cess slide. Flypaper bearings casper slide Rob Roskopp hang up hospital flip hurricane no comply. Hang ten rocket air fastplant boneless bigspin rail slide feeble. Frontside drop in wall ride concave 270 launch ramp face plant. Heel flip pump tailslide skate key deck crail grab Daggers coping. Pop shove-it hang-up street sketchy coping ledge rock and roll.", 1, 1, 0);
INSERT INTO sites(language, title, content, template, pos, visible) VALUES ("de", "inhalt zwei", "Fooo", "foo", 2, "", 0);
INSERT INTO sites(language, title, content, pos, visible) VALUES ("de", "inhalt drei", "Skate ipsum dolor sit amet, Chris Buchinsky noseblunt slide 900 betty frigid air gap wall ride flail. 50-50 crooked grind hardware steps tail shinner Vatoland birdie. Sketchy Saran Wrap shinner hand rail bank backside rad.", 3, 1, 0);
INSERT INTO sites(language, title, content, pos, visible) VALUES ("de", "inhalt vier", "Skate ipsum dolor sit amet, Chris Buchinsky noseblunt slide 900 betty frigid air gap wall ride flail. 50-50 crooked grind hardware steps tail shinner Vatoland birdie. Sketchy Saran Wrap shinner hand rail bank backside rad.", 4, 1, 0);