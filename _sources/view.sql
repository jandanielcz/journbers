create or replace view valid_trips 
as
select 
  trips.id,
  trips.car,
  cars.registration as car_registration,
  cars.name as car_name,
  
  trips.driver,
  u.fullname as driver_name,
  
  trips.added_by,
  trips.added_on,
  a.fullname AS added_by_name,
  
  trips.start_odometer,
  trips.start_place,
  trips.start_date,
  
  if(trips.is_personal = 1, null, trips.target_client) as target_client,
  if(trips.is_personal = 1, null, trips.target_place) as target_place,
  
  trips.end_odometer,
  trips.end_place,
  trips.end_date,
  
  trips.is_personal,
  trips.and_back,
  
  trips.note,
  
  if(trips.end_odometer is not null, trips.end_odometer - trips.start_odometer, null) as trip_length,
  if(trips.end_date IS NOT NULL, TIMESTAMPDIFF(MINUTE, trips.start_date, trips.end_date), null) AS trip_duration
  
from trips 
join
  users AS u on trips.driver = users.id
JOIN users AS a on
  trips.added_by = users.id
join
  cars on trips.car = cars.id
where 
  trips.removed_on is null
  and
  trips.overwriten_by is null;