DROP TABLE IF EXISTS easy_audit_users;
DROP TABLE IF EXISTS easy_audit_customer_forms;
DROP TABLE IF EXISTS easy_audit_customers;
DROP TABLE IF EXISTS easy_audit_form_template_fields;
DROP TABLE IF EXISTS easy_audit_form_template_sections;
DROP TABLE IF EXISTS easy_audit_form_templates;
DROP TABLE IF EXISTS easy_audit_form_template_optionset_values;
DROP TABLE IF EXISTS easy_audit_form_template_optionsets;



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


CREATE TABLE easy_audit_form_template_optionsets (
  id int unsigned NOT NULL AUTO_INCREMENT,
  name varchar(200) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE easy_audit_form_template_optionset_values (
  id int unsigned NOT NULL AUTO_INCREMENT,
  optionset_id int unsigned NOT NULL,
  label varchar(200) NOT NULL,
  value varchar(20) NOT NULL,
  value_numeric int unsigned DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE easy_audit_form_templates (
  id int unsigned NOT NULL AUTO_INCREMENT,
  name varchar(200) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE easy_audit_form_template_sections (
  id int unsigned NOT NULL AUTO_INCREMENT,
  form_template_id int unsigned NOT NULL,
  position int unsigned NOT NULL,
  name varchar(200) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE easy_audit_form_template_fields (
  id int unsigned NOT NULL AUTO_INCREMENT,
  form_template_id int unsigned NOT NULL,
  form_template_section_id int unsigned NOT NULL,
  optionset_id int unsigned DEFAULT NULL,
  position int unsigned NOT NULL,
  text varchar(4000) DEFAULT NULL,
  type varchar(20) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE easy_audit_customers (
  id int unsigned NOT NULL AUTO_INCREMENT,
  name varchar(4000) NOT NULL,
  email varchar(4000) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE easy_audit_customer_forms (
  customer_id int unsigned NOT NULL,
  form_template_id int unsigned NOT NULL,
  PRIMARY KEY (customer_id, form_template_id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE easy_audit_audits (
  id int unsigned NOT NULL AUTO_INCREMENT,         
  customer_id int unsigned NOT NULL,
  form_template_id int unsigned NOT NULL,
  date date DEFAULT NULL,
  user_id int unsigned NOT NULL,
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
  '$2y$10$ChGDc1dcEvxZFEFofq5crOIM.vgTtWzWjBZYjt45f9i9D/acwZfAK', -- mypassword
  'Administrador',
  'admin',
  '2016-09-21 19:38:38',
  '2016-09-21 19:38:38'
);


INSERT INTO easy_audit_form_template_optionsets (id, name) VALUES (1, 'A, B, C');

INSERT INTO easy_audit_form_template_optionset_values (optionset_id, label, value, value_numeric) VALUES (1, 'A', 'A', 10);
INSERT INTO easy_audit_form_template_optionset_values (optionset_id, label, value, value_numeric) VALUES (1, 'B', 'B', 5);
INSERT INTO easy_audit_form_template_optionset_values (optionset_id, label, value, value_numeric) VALUES (1, 'C', 'C', 0);
