CREATE TABLE kbb_block (
	wid INTEGER,
	user INTEGER,
	time INTEGER,
	x INTEGER,
	y INTEGER,
	z INTEGER,
	type INTEGER,
	meta INTEGER,
	action INTEGER,
	rolled_back INTEGER
);

CREATE TABLE logs(
    level TEXT,
    x INTEGER,
    y INTEGER,
    z INTEGER,
    name TEXT,
    blockid INTEGER,
    meta INTEGER,
    action INTEGER,
    time INTEGER
);

CREATE INDEX block_index ON kbb_block ( 
    z,
    time,
    wid,
    x 
);

CREATE INDEX block_user_index ON kbb_block ( 
    user,
    time 
);

CREATE INDEX block_type_index ON kbb_block ( 
    type,
    time 
);

CREATE TABLE kbb_user (
	id INTEGER PRIMARY KEY,
	user TEXT,
	time INTEGER
);

CREATE INDEX user_index ON kbb_user ( 
    user 
);

CREATE TABLE kbb_world (
	id INTEGER PRIMARY KEY,
	world TEXT
);

CREATE TABLE kbb_session (
	wid INTEGER,
	user INTEGER,
	time INTEGER,
	x INTEGER,
	y INTEGER,
	z INTEGER,
	action INTEGER
);

CREATE INDEX session_index ON kbb_session ( 
    z,
    time,
    wid,
    x
);

CREATE INDEX session_action_index ON kbb_session ( 
    action,
    time
);

CREATE INDEX session_user_index ON kbb_session ( 
    user,
    time
);

CREATE INDEX session_time_index ON kbb_session ( 
    time
);

CREATE TABLE kbb_command ( 
    time INTEGER,
    user INTEGER,
    message TEXT
);

CREATE INDEX command_index ON kbb_command ( 
    time
);

CREATE INDEX command_user_index ON kbb_command ( 
    user,
    time
);

CREATE TABLE kbb_chat (
	user INTEGER,
	time INTEGER,
	message TEXT
);

CREATE INDEX chat_index ON kbb_chat ( 
    time 
);

CREATE INDEX chat_user_index ON kbb_chat ( 
    user,
    time
);

CREATE TABLE kbb_version ( 
    time INTEGER,
    version TEXT
);