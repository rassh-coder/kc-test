<b>*Laravel required*</b>

<b>Requests needs JWT token in header Authorization: Bearer ***** * </b>
Routes: <br>
-----
<b>User routes</b><br>
/api/v1/registration - Registration body - login: string, password: string *Not need Auth header* <br>
/api/v1/login - Login body - login: string, password: string *Not need Auth header* <br>
/api/v1/logout - Logout, destroy JWT token <br>
/api/v1/me - Fetch info about logged user <br>
/api/v1/transaction - Fetch all user transactions <br>
/api/v1/transaction/{transaction_id} - Fetch user transaction by id<br>
-----
/api/v1/product - Fetch products list <br>
/api/v1/product/{id} - Fetch info about product by product id >br>
/api/v1/product/{id}/buy - Buy product <br>
/api/v1/product/{id}/rent?time=4/8/12/24 - Rent product for time *Query param is required* <br>
/api/v1/user-product - Fetch all user products <br>
/api/v1/user-product/{product_id} - Fetch user product by product id and check status (have access and not expired) *Required product id (not user product id)* <br>
-----
<b>CRON</b><br>
Add to your server this cron task:<br>
<b>* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1</b>

P.S. для тз <br>
Можно улучшить все с помощью кеша и разгрузить тем самым БД