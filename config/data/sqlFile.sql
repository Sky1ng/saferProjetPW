

CREATE FUNCTION get_quoted_values(longtext_field LONGTEXT) RETURNS VARCHAR(10000) BEGIN
  DECLARE start INT DEFAULT 1;
  DECLARE end INT DEFAULT 1;
  DECLARE result VARCHAR(10000) DEFAULT '';
  DECLARE value VARCHAR(10000);

  WHILE start > 0 DO
    SET start = LOCATE('"', longtext_field, end + 1);
    SET end = LOCATE('"', longtext_field, start + 1);
    SET value = SUBSTRING(longtext_field, start + 1, end - start - 1);
    IF start > 0 THEN
      SET result = CONCAT(result, value, ',');
    END IF;
  END WHILE;

  RETURN TRIM(TRAILING ',' FROM result);
END;
