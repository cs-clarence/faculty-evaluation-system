CREATE OR REPLACE FUNCTION set_order_numerator()
RETURNS TRIGGER AS $$
DECLARE
    current_max INTEGER;
BEGIN
  IF NEW.order_numerator = 0 THEN
      -- Use dynamic SQL to query the maximum order_numerator value from the table
      EXECUTE format('SELECT COALESCE(MAX(order_numerator), 0) FROM %I', TG_TABLE_NAME)
        INTO current_max;

      NEW.order_numerator := current_max + 1;
  END IF;
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;


CREATE OR REPLACE TRIGGER set_order_numerator_form_sections
BEFORE INSERT ON form_sections
FOR EACH ROW
EXECUTE FUNCTION set_order_numerator();


CREATE TRIGGER set_order_numerator_form_questions
BEFORE INSERT ON form_questions
FOR EACH ROW
EXECUTE FUNCTION set_order_numerator();


CREATE OR REPLACE TRIGGER set_order_numerator_form_question_options
BEFORE INSERT ON form_question_options
FOR EACH ROW
EXECUTE FUNCTION set_order_numerator();
