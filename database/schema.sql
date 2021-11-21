DROP TABLE IF EXISTS easy_audit_users;



CREATE TABLE easy_audit_users (
  id int unsigned NOT NULL AUTO_INCREMENT,
  username varchar(50) DEFAULT NULL,
  password varchar(255) DEFAULT NULL,
  name varchar(255) DEFAULT NULL,
  role varchar(20) DEFAULT NULL,
  created datetime DEFAULT NULL,
  modified datetime DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;



INSERT INTO easy_audit_users (
  id,
  username,
  password,
  name,
  role,
  created,
  modified
) VALUES (
  1,
  'easy-admin',
  '91dfd9ddb4198affc5c194cd8ce6d338fde470e2', -- mypassword
  'Administrador',
  'admin',
  '2016-09-21 19:38:38',
  '2016-09-21 19:38:38'
);
