DROP TABLE IF EXISTS siteinfo;
DROP TABLE IF EXISTS sites;
DROP TABLE IF EXISTS user;

CREATE TABLE siteinfo(
	site_title		TEXT,
	site_theme		TEXT,
	site_headline	TEXT
);

CREATE TABLE user(
	user_email		TEXT
);

CREATE TABLE sites(
	id				INTEGER PRIMARY KEY AUTOINCREMENT,
	title			INTEGER,
	mtime			INTEGER,
	content		TEXT,
	pos				INTEGER,
	visible		INTEGER
);

INSERT INTO siteinfo(site_title, site_theme, site_headline) VALUES ("Wurzelstrang Demo", "Standart", "Wurzelstrang");

INSERT INTO user(user_email) VALUES ("post@bombenlabor.de");

INSERT INTO sites(title, content, pos, visible) VALUES ("inhalt eins", "Skate ipsum dolor sit amet, Chris Buchinsky noseblunt slide 900 betty frigid air gap wall ride flail. 50-50 crooked grind hardware steps tail shinner Vatoland birdie. Sketchy Saran Wrap shinner hand rail bank backside rad. Hang-up helipop sketchy wax hip ho-ho face plant. Carve mongo dude John Lucero ollie hole skate or die grab cess slide. Flypaper bearings casper slide Rob Roskopp hang up hospital flip hurricane no comply. Hang ten rocket air fastplant boneless bigspin rail slide feeble. Frontside drop in wall ride concave 270 launch ramp face plant. Heel flip pump tailslide skate key deck crail grab Daggers coping. Pop shove-it hang-up street sketchy coping ledge rock and roll.", 1, 1);
INSERT INTO sites(title, content, pos, visible) VALUES ("inhalt zwei", "Skate ipsum dolor sit amet, Chris Buchinsky noseblunt slide 900 betty frigid air gap wall ride flail. 50-50 crooked grind hardware steps tail shinner Vatoland birdie. Sketchy Saran Wrap shinner hand rail bank backside rad. Hang-up helipop sketchy wax hip ho-ho face plant. Carve mongo dude John Lucero ollie hole skate or die grab cess slide. Flypaper bearings casper slide Rob Roskopp hang up hospital flip hurricane no comply. Hang ten rocket air fastplant boneless bigspin rail slide feeble. Frontside drop in wall ride concave 270 launch ramp face plant. Heel flip pump tailslide skate key deck crail grab Daggers coping. Pop shove-it hang-up street sketchy coping ledge rock and roll.", 2, "");
INSERT INTO sites(title, content, pos, visible) VALUES ("inhalt drei", "Skate ipsum dolor sit amet, Chris Buchinsky noseblunt slide 900 betty frigid air gap wall ride flail. 50-50 crooked grind hardware steps tail shinner Vatoland birdie. Sketchy Saran Wrap shinner hand rail bank backside rad. Hang-up helipop sketchy wax hip ho-ho face plant. Carve mongo dude John Lucero ollie hole skate or die grab cess slide. Flypaper bearings casper slide Rob Roskopp hang up hospital flip hurricane no comply. Hang ten rocket air fastplant boneless bigspin rail slide feeble. Frontside drop in wall ride concave 270 launch ramp face plant. Heel flip pump tailslide skate key deck crail grab Daggers coping. Pop shove-it hang-up street sketchy coping ledge rock and roll.", 3, 1);
INSERT INTO sites(title, content, pos, visible) VALUES ("inhalt vier", "Skate ipsum dolor sit amet, Chris Buchinsky noseblunt slide 900 betty frigid air gap wall ride flail. 50-50 crooked grind hardware steps tail shinner Vatoland birdie. Sketchy Saran Wrap shinner hand rail bank backside rad. Hang-up helipop sketchy wax hip ho-ho face plant. Carve mongo dude John Lucero ollie hole skate or die grab cess slide. Flypaper bearings casper slide Rob Roskopp hang up hospital flip hurricane no comply. Hang ten rocket air fastplant boneless bigspin rail slide feeble. Frontside drop in wall ride concave 270 launch ramp face plant. Heel flip pump tailslide skate key deck crail grab Daggers coping. Pop shove-it hang-up street sketchy coping ledge rock and roll.", 4, 1);
INSERT INTO sites(title, content, pos, visible) VALUES ("inhalt fünf", "Skate ipsum dolor sit amet, Chris Buchinsky noseblunt slide 900 betty frigid air gap wall ride flail. 50-50 crooked grind hardware steps tail shinner Vatoland birdie. Sketchy Saran Wrap shinner hand rail bank backside rad. Hang-up helipop sketchy wax hip ho-ho face plant. Carve mongo dude John Lucero ollie hole skate or die grab cess slide. Flypaper bearings casper slide Rob Roskopp hang up hospital flip hurricane no comply. Hang ten rocket air fastplant boneless bigspin rail slide feeble. Frontside drop in wall ride concave 270 launch ramp face plant. Heel flip pump tailslide skate key deck crail grab Daggers coping. Pop shove-it hang-up street sketchy coping ledge rock and roll.", 5, 1);
INSERT INTO sites(title, content, pos, visible) VALUES ("inhalt sechs", "Skate ipsum dolor sit amet, Chris Buchinsky noseblunt slide 900 betty frigid air gap wall ride flail. 50-50 crooked grind hardware steps tail shinner Vatoland birdie. Sketchy Saran Wrap shinner hand rail bank backside rad. Hang-up helipop sketchy wax hip ho-ho face plant. Carve mongo dude John Lucero ollie hole skate or die grab cess slide. Flypaper bearings casper slide Rob Roskopp hang up hospital flip hurricane no comply. Hang ten rocket air fastplant boneless bigspin rail slide feeble. Frontside drop in wall ride concave 270 launch ramp face plant. Heel flip pump tailslide skate key deck crail grab Daggers coping. Pop shove-it hang-up street sketchy coping ledge rock and roll.", 6, 1);
INSERT INTO sites(title, content, pos, visible) VALUES ("inhalt sieben", "Skate ipsum dolor sit amet, Chris Buchinsky noseblunt slide 900 betty frigid air gap wall ride flail. 50-50 crooked grind hardware steps tail shinner Vatoland birdie. Sketchy Saran Wrap shinner hand rail bank backside rad. Hang-up helipop sketchy wax hip ho-ho face plant. Carve mongo dude John Lucero ollie hole skate or die grab cess slide. Flypaper bearings casper slide Rob Roskopp hang up hospital flip hurricane no comply. Hang ten rocket air fastplant boneless bigspin rail slide feeble. Frontside drop in wall ride concave 270 launch ramp face plant. Heel flip pump tailslide skate key deck crail grab Daggers coping. Pop shove-it hang-up street sketchy coping ledge rock and roll.", 7, 1);
INSERT INTO sites(title, content, pos, visible) VALUES ("inhalt acht", "Skate ipsum dolor sit amet, Chris Buchinsky noseblunt slide 900 betty frigid air gap wall ride flail. 50-50 crooked grind hardware steps tail shinner Vatoland birdie. Sketchy Saran Wrap shinner hand rail bank backside rad. Hang-up helipop sketchy wax hip ho-ho face plant. Carve mongo dude John Lucero ollie hole skate or die grab cess slide. Flypaper bearings casper slide Rob Roskopp hang up hospital flip hurricane no comply. Hang ten rocket air fastplant boneless bigspin rail slide feeble. Frontside drop in wall ride concave 270 launch ramp face plant. Heel flip pump tailslide skate key deck crail grab Daggers coping. Pop shove-it hang-up street sketchy coping ledge rock and roll.", 8, 1);