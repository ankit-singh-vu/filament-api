# How To Setup

1. Clone the repository
2. go to the application folder
3. run `mv .env.template .env`
4. Then SET variables like DOCKER_USER, DOCKER_PASS, APP_URL and MYSQL_ROOT_PASSWORD in .env file 
   without these app will not come up
4. run `chmod +x inital_setup.sh`
5. run `sudo ./inital_setup.sh your_non_root_user`
5. run `vagrant up` 
6. Now You will see admin username and password inside the logs


# --------- USER --------------------------------------------------------------------------------

# registration user
curl --location 'https://192.168.62.101/api/user/registration' \
--header 'Content-Type: application/json' \
--data '{
	"email": "dev2",
	"first": "dev2",
	"middle": "dev2",
	"last": "dev2",
	"password": "dev2",
	"tenants": "dev2"
}'


# login user
curl --location 'https://192.168.62.101/api/user/login' \
--header 'Content-Type: application/json' \
--data '{
    "access_key": "dev",
    "access_secret": "dev"
}'

# delete user
curl --location 'https://192.168.62.101/api/user/delete?email=dev2' \
--header 'access-token: fa84e194-d441-4cf2-853b-d77d8fbfefe7-ec194e01-bc4a-437b-815c-91203c801643'


# add user (Owner/Admin/Staff) to existing Tenant
curl --location 'https://192.168.62.101/api/user/adduser?tenant_id=2&role=Staff' \
--header 'Content-Type: application/json' \
--data '{
	"email": "dev3",
	"first": "dev3",
	"middle": "dev3",
	"last": "dev3",
	"password": "dev3"
}'

# tenant views its own catalog
curl --location 'https://192.168.62.101/api/user/2/catalog' \
--header 'access-token: 427d069e-53c9-42e1-9066-d72606ae3dd5-3657ce28-571b-4cb5-87b9-b521345b2a8c'


# --------- ADMIN --------------------------------------------------------------------------------

# registration admin
curl --location 'https://192.168.62.101/api/admin/registration' \
--header 'Content-Type: application/json' \
--data-raw '{
    "fullname": "dev",
    "email": "dev@convesio.com",
    "password": "admin",
    "access": "1",
    "status": "1",
    "timezone": "",
    "last_login": "1675922829",
    "last_ip": null,
    "contact_number": "7098998989",
    "role": null,
    "two_fa": "0",
    "google_auth_key": "BFOGPNGML35PRFS2",
    "last_scan": "1666674656"
}'


# login admin
curl --location 'https://192.168.62.101/api/admin/login' \
--header 'Content-Type: application/json' \
--data-raw '{
    "email": "dev@convesio.com",
    "password": "admin",
    "type": "Web"
}'

# delete admin
curl --location --request DELETE 'https://192.168.62.101/api/admin/5' \
--header 'access-token: b5f25995-33a7-40e8-859e-99aa75f00c09-bfd3b1c2-848e-44aa-8453-cfde7c6c21f1'


# --------- PARTNER ---------------------------------------------------------------------------------------

# get global catalog
curl --location 'https://192.168.62.101/api/partner/catalog/global' \
--header 'access-token: a2868880-5e53-442e-ac2d-0d40365c5820-50a0fb92-7fea-4a2c-89e0-9f794a4e20e9'

# get private catalog
curl --location 'https://192.168.62.101/api/partner/catalog/2/private' \
--header 'access-token: a2868880-5e53-442e-ac2d-0d40365c5820-50a0fb92-7fea-4a2c-89e0-9f794a4e20e9'

# update tenant catlog list
curl --location --request PUT 'https://192.168.62.101/api/partner/2/catalog/edit' \
--header 'access-token: 427d069e-53c9-42e1-9066-d72606ae3dd5-3657ce28-571b-4cb5-87b9-b521345b2a8c' \
--header 'Content-Type: application/json' \
--data '{
    "status":0,
    "catalog_item_id":3,
    "type": "global"
}'