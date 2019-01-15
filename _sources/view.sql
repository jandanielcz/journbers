create or replace view valid_trips 
as
select 
  trips.id,
  trips.car,
  cars.registration as car_registration,
  cars.name as car_name,
  
  trips.driver,
  users.fullname as driver_name,
  
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
  
  if(trips.end_odometer is not null, end_odometer - trips.start_odometer, null) as trip_length
  
from trips 
join
  users on trips.driver = users.id
join
  cars on trips.car = cars.id
where 
  trips.removed_on is null
  and
  trips.overwriten_by is null;