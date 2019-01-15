select 
  valid_trips.*
from valid_trips
where 
  car = 'golf'
  and
  datediff(now(), start_date) <= 60
order by start_odometer desc
;