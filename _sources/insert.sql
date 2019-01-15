insert into trips values (
	null,                 -- id
	null,                 -- overwriten by
	'golf',               -- car
	'dan',                -- driver
	                  -- added     
	'dan',            
	now(),                
							-- removed
	null,
	null,
							-- start
	1000,
	'Brno',
	'2019-01-15T08:10',
							-- target
	null, 					 -- client
	'Praha',              -- place
							-- end
	1200,
	null,
	'2019-01-15T11:20',
	
	false,                -- is personal
	true                  -- and back
);