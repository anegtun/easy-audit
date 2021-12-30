DROP TABLE IF EXISTS easy_audit_audit_field_measure_values;
DROP TABLE IF EXISTS easy_audit_audit_field_optionset_values;
DROP TABLE IF EXISTS easy_audit_audits;
DROP TABLE IF EXISTS easy_audit_customer_forms;
DROP TABLE IF EXISTS easy_audit_customers;
DROP TABLE IF EXISTS easy_audit_form_template_fields_optionset;
DROP TABLE IF EXISTS easy_audit_form_template_sections;
DROP TABLE IF EXISTS easy_audit_form_templates;
DROP TABLE IF EXISTS easy_audit_form_template_optionset_values;
DROP TABLE IF EXISTS easy_audit_form_template_optionsets;
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


CREATE TABLE easy_audit_form_template_optionsets (
  id int unsigned NOT NULL AUTO_INCREMENT,
  name varchar(200) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE easy_audit_form_template_optionset_values (
  id int unsigned NOT NULL AUTO_INCREMENT,
  is_default int unsigned NOT NULL DEFAULT 0,
  optionset_id int unsigned NOT NULL,
  label varchar(200) NOT NULL,
  value varchar(20) NOT NULL,
  value_numeric float unsigned DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_FormTemplateOptionsetValue_FormTemplateOptionset
    FOREIGN KEY (optionset_id)
    REFERENCES easy_audit_form_template_optionsets(id)
    ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE easy_audit_form_templates (
  id int unsigned NOT NULL AUTO_INCREMENT,
  disabled int unsigned NOT NULL DEFAULT 0,
  name varchar(200) DEFAULT NULL,
  type varchar(20) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE easy_audit_form_template_sections (
  id int unsigned NOT NULL AUTO_INCREMENT,
  form_template_id int unsigned NOT NULL,
  position int unsigned NOT NULL,
  name varchar(200) DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_FormTemplateSection_FormTemplate
    FOREIGN KEY (form_template_id)
    REFERENCES easy_audit_form_templates(id)
    ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE easy_audit_form_template_fields_optionset (
  id int unsigned NOT NULL AUTO_INCREMENT,
  form_template_id int unsigned NOT NULL,
  form_template_section_id int unsigned NOT NULL,
  optionset_id int unsigned DEFAULT NULL,
  position int unsigned NOT NULL,
  text varchar(4000) DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_FormTemplateField_FormTemplate
    FOREIGN KEY (form_template_id)
    REFERENCES easy_audit_form_templates(id)
    ON DELETE CASCADE,
  CONSTRAINT FK_FormTemplateField_FormTemplateSection
    FOREIGN KEY (form_template_section_id)
    REFERENCES easy_audit_form_template_sections(id)
    ON DELETE CASCADE
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
  PRIMARY KEY (customer_id, form_template_id),
  CONSTRAINT FK_CustomerFormTemplate_Customer
    FOREIGN KEY (customer_id)
    REFERENCES easy_audit_customers(id)
    ON DELETE CASCADE,
  CONSTRAINT FK_CustomerFormTemplate_FormTemplate
    FOREIGN KEY (form_template_id)
    REFERENCES easy_audit_form_templates(id)
    ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE easy_audit_audits (
  id int unsigned NOT NULL AUTO_INCREMENT,
  customer_id int unsigned NOT NULL,
  date date DEFAULT NULL,
  auditor_user_id int unsigned NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_Audit_Customer
    FOREIGN KEY (customer_id)
    REFERENCES easy_audit_customers(id),
  CONSTRAINT FK_Audit_User
    FOREIGN KEY (auditor_user_id)
    REFERENCES easy_audit_users(id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE easy_audit_audit_forms (
  audit_id int unsigned NOT NULL,
  form_template_id int unsigned NOT NULL,
  PRIMARY KEY (audit_id, form_template_id),
  CONSTRAINT FK_AuditFormTemplate_Audit
    FOREIGN KEY (audit_id)
    REFERENCES easy_audit_audits(id)
    ON DELETE CASCADE,
  CONSTRAINT FK_AuditFormTemplate_FormTemplate
    FOREIGN KEY (form_template_id)
    REFERENCES easy_audit_form_templates(id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE easy_audit_audit_field_optionset_values (
  id int unsigned NOT NULL AUTO_INCREMENT,
  audit_id int unsigned NOT NULL,
  form_template_id int unsigned NOT NULL,
  form_template_field_id int unsigned NOT NULL,
  optionset_value_id int unsigned NOT NULL,
  observations varchar(4000) DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_AuditOptionsetValues_AuditFormTemplate
    FOREIGN KEY (audit_id, form_template_id)
    REFERENCES easy_audit_audit_forms(audit_id, form_template_id)
    ON DELETE CASCADE,
  CONSTRAINT FK_AuditOptionsetValues_FormTemplateOptionsetField
    FOREIGN KEY (form_template_field_id)
    REFERENCES easy_audit_form_template_fields_optionset(id),
  CONSTRAINT FK_AuditOptionsetValues_FormTemplateOptionsetValue
    FOREIGN KEY (optionset_value_id),
    REFERENCES easy_audit_form_template_optionset_values(id),
  CONSTRAINT UQ_AuditOptionsetValues_AuditFormTemplateField
    UNIQUE (audit_id,form_template_field_id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE easy_audit_audit_field_measure_values (
  id int unsigned NOT NULL AUTO_INCREMENT,
  audit_id int unsigned NOT NULL,
  form_template_id int unsigned NOT NULL,
  item varchar(4000) DEFAULT NULL,
  expected float DEFAULT NULL,
  actual float DEFAULT NULL,
  threshold float DEFAULT NULL,
  observations varchar(4000) DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_AuditMeasureValues_AuditFormTemplate
    FOREIGN KEY (audit_id, form_template_id)
    REFERENCES easy_audit_audit_forms(audit_id, form_template_id)
    ON DELETE CASCADE
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

INSERT INTO easy_audit_form_template_optionset_values (optionset_id, label, value, value_numeric, is_default) VALUES (1, 'A', 'A', 10, 1);
INSERT INTO easy_audit_form_template_optionset_values (optionset_id, label, value, value_numeric, is_default) VALUES (1, 'B', 'B', 5, 0);
INSERT INTO easy_audit_form_template_optionset_values (optionset_id, label, value, value_numeric, is_default) VALUES (1, 'C', 'C', 0, 0);
