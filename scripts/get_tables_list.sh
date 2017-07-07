 mysql -u root -p -N information_schema -e "select table_name from tables where table_schema = 'termt_u9_ru' and table_name like 'm2_%'" > tables.txt
