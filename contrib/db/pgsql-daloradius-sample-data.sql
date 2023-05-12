


CREATE TABLE radacct (
	RadAcctId		BIGSERIAL PRIMARY KEY,
	AcctSessionId		VARCHAR(32) NOT NULL,
	AcctUniqueId		VARCHAR(32) NOT NULL,
	UserName		VARCHAR(253),
	Realm			VARCHAR(30),
	NASIPAddress		INET NOT NULL,
	NASPortId		BIGINT,
	NASPortType		VARCHAR(32),
	AcctStartTime		TIMESTAMP with time zone,
	AcctStopTime		TIMESTAMP with time zone,
	AcctSessionTime		BIGINT,
	AcctAuthentic		VARCHAR(32),
	ConnectInfo_start	VARCHAR(32),
	ConnectInfo_stop	VARCHAR(32),
	AcctInputOctets		BIGINT,
	AcctOutputOctets	BIGINT,
	CalledStationId		VARCHAR(50),
	CallingStationId	VARCHAR(50),
	AcctTerminateCause	VARCHAR(32),
	ServiceType		VARCHAR(32),
	FramedProtocol		VARCHAR(32),
	FramedIPAddress		INET,
	AcctStartDelay		BIGINT,
	AcctStopDelay		BIGINT
);
-- This index may be usefull..
-- CREATE UNIQUE INDEX radacct_whoson on radacct (AcctStartTime, nasipaddress);

-- For use by onoff-, update-, stop- and simul_* queries
CREATE INDEX radacct_active_user_idx ON radacct (userName) WHERE AcctStopTime IS NULL;
-- and for common statistic queries:
CREATE INDEX radacct_start_user_idx ON radacct (acctStartTime, UserName);
-- and, optionally
-- CREATE INDEX radacct_stop_user_idx ON radacct (acctStopTime, UserName);








CREATE TABLE radcheck (
	id		SERIAL PRIMARY KEY,
	UserName	VARCHAR(30) DEFAULT '' NOT NULL,
	Attribute	VARCHAR(30),
	op VARCHAR(2)	NOT NULL DEFAULT '==',
	Value		VARCHAR(40)
);
create index radcheck_UserName on radcheck (UserName,Attribute);

-- create index radcheck_UserName_lower on radcheck (lower(UserName),Attribute);


CREATE TABLE radgroupcheck (
	id		SERIAL PRIMARY KEY,
	GroupName	VARCHAR(20) DEFAULT '' NOT NULL,
	Attribute	VARCHAR(40),
	op		VARCHAR(2) NOT NULL DEFAULT '==',
	Value		VARCHAR(40)
);
create index radgroupcheck_GroupName on radgroupcheck (GroupName,Attribute);


CREATE TABLE radgroupreply (
	id		SERIAL PRIMARY KEY,
	GroupName	VARCHAR(20) DEFAULT '' NOT NULL,
	Attribute	VARCHAR(40),
	op		VARCHAR(2) NOT NULL DEFAULT '=',
	Value		VARCHAR(40)
);
create index radgroupreply_GroupName on radgroupreply (GroupName,Attribute);


CREATE TABLE radreply (
	id		SERIAL PRIMARY KEY,
	UserName	VARCHAR(30) DEFAULT '' NOT NULL,
	Attribute	VARCHAR(30),
	op		VARCHAR(2) NOT NULL DEFAULT '=',
	Value		VARCHAR(40)
);
create index radreply_UserName on radreply (UserName,Attribute);

-- create index radreply_UserName_lower on radreply (lower(UserName),Attribute);


CREATE TABLE usergroup (
	id		SERIAL PRIMARY KEY,
	UserName	VARCHAR(30) DEFAULT '' NOT NULL,
	GroupName	VARCHAR(30)
);
create index usergroup_UserName on usergroup (UserName);

-- create index usergroup_UserName_lower on usergroup (lower(UserName));


CREATE TABLE realmgroup (
	id		SERIAL PRIMARY KEY,
	RealmName	VARCHAR(30) DEFAULT '' NOT NULL,
	GroupName	VARCHAR(30)
);
create index realmgroup_RealmName on realmgroup (RealmName);


CREATE TABLE realms (
	id		SERIAL PRIMARY KEY,
	realmname	VARCHAR(64),
	nas		VARCHAR(128),
	authport	int4,
	options		VARCHAR(128) DEFAULT ''
);


CREATE TABLE nas (
	ipaddr		INET PRIMARY KEY,
	shortname	VARCHAR(32) NOT NULL,
	secret		VARCHAR(60) NOT NULL,
	nasname		VARCHAR(128),
	type		VARCHAR(30),
	ports		int4,
	community	VARCHAR(50),
	snmp		VARCHAR(10),
	naslocation	VARCHAR(32)
);

--
-- Table structure for table 'radpostauth'
--

CREATE TABLE radpostauth (
	id		BIGSERIAL PRIMARY KEY,
	username	VARCHAR(253) NOT NULL,
	pass		VARCHAR(128),
	reply		VARCHAR(32),
	authdate	TIMESTAMP with time zone NOT NULL default 'now'
) ;



--
-- Table structure for daloRADIUS's specific tables
--

CREATE TABLE hotspots (
  id BIGSERIAL PRIMARY KEY,
  name varchar(32),
  mac varchar(32),
  geocode varchar(128) 
); 

CREATE TABLE operators (
  id BIGSERIAL PRIMARY KEY,
  username varchar(32),
  password varchar(32) 
);

INSERT INTO operators VALUES (1,'administrator','radius');

CREATE TABLE rates (
  id BIGSERIAL PRIMARY KEY,
  type varchar(32),
  cardbank float,
  rate float 
);



--
-- Table structure for table 'dictionary'
-- This is not currently used by FreeRADIUS
--
-- CREATE TABLE dictionary (
--     id              SERIAL PRIMARY KEY,
--     Type            VARCHAR(30),
--     Attribute       VARCHAR(64),
--     Value           VARCHAR(64),
--     Format          VARCHAR(20),
--     Vendor          VARCHAR(32)
-- );


CREATE FUNCTION DATE_SUB(date,int4,text) RETURNS DATE AS '
DECLARE
        var1 date;
        var2 text;
BEGIN
        var2 = $2 || '' '' || $3;
        SELECT INTO var1
                to_date($1 - var2::interval, ''YYYY-MM-DD'');
RETURN var1;
END;' LANGUAGE 'plpgsql';
