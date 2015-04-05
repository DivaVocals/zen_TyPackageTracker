SELECT @tyid:=configuration_group_id FROM configuration_group
WHERE configuration_group_title= 'Ty Package Tracker';
DELETE FROM configuration WHERE configuration_group_id = @tyid;
DELETE FROM configuration_group WHERE configuration_group_id = @tyid;
DELETE FROM admin_pages WHERE page_key='configTyPackageTracker';

ALTER TABLE `orders_status_history` 
DROP `track_id1`,
DROP `track_id2`,
DROP `track_id3`,
DROP `track_id4`,
DROP `track_id5`;