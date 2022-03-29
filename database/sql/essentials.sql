--Function to search things with ç and accents
--Create Role postgres if not exists
CREATE OR REPLACE FUNCTION ignore_accents(text)
RETURNS text AS
$BODY$
SELECT TRANSLATE($1,'áàãâäÁÀÃÂÄéèêëẽÉÈÊËẼíìîïĩÍÌÎÏĨóòõôöÓÒÕÔÖúùûüũÚÙÛÜŨñÑçÇÿýÝ', 'aaaaaAAAAAeeeeEEEEEEiiiiiIIIIIoooooOOOOOuuuuuUUUUUnNcCyyY')
$BODY$
LANGUAGE sql IMMUTABLE STRICT
COST 100;
ALTER FUNCTION ignore_accents(text)
OWNER TO postgres;
COMMENT ON FUNCTION ignore_accents(text) IS 'Ignore accents';