SELECT
codes.code,
codes.code_text,
ydp_protocol.name
FROM `codes_to_protocol` 
JOIN codes ON codes_to_protocol.code_id = codes.code_id
JOIN ydp_protocol ON codes_to_protocol.protocol_id = ydp_protocol.id
