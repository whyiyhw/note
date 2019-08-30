## mysql的关联update语句
```
update 表名 set 表.key1 = 表.value1,表.key2=value.key2 where id = 1

多表的关联更新
update a,b set a.key1 = b.value2,a.key2=b.value1 where a.id = b.id
```
```
存在A(id,sex,par,c1,c2)
B(id,age,c1,c2)

更新B表中age大于50 c1,c2到a表中的 c1,c2
 
update A,B set A.c1 = B.c1, A.c2=B.c2 where A.id = b.id and B.age > 50;

update A inner join B on A.id = B.id set A.c1 = B.c1, A.c2=B.c2 where B.age > 50;
```
## mysql的关联查询语句
- 六种关联查询
    - 交叉连接(cross join)
        - select * from a,b,c; 
    - inner join 内连接
        -  多表中同时符合某种条件的数据记录的集合
        -  比外连接更加精确,两或者多张表同时存在才更改
        - 直接缩写join  不加 left/right/inner 就是内连接
        - 等值连接/不等值连接
        - 自连接`select * from a t1 join a t2 on t1.id=t2.pid`(可以解决分类的问题)
        - 
- left/right join 外连接
    - 以一张表为主,以一张表查出来的数据去匹配另一张表的数据,如果找不到以null来填充
- union /union all 联合查询
    -   select * from a union select * from b ...
    -   就是把多个结果集纵向拼接在一起,以union前的结果为基准,需要注意的是联合查询的列数要相等,相同的记录会被合并
    -   union all  不会合并记录行
- full join (暂不支持) 全连接
    - 全连接就是 横纵数据都合并
    - 可以先左连接  再 union链接 
    - `select * from a left join b on a.id = b.id union select * from a right join b on a.id = b.id;`
-  sql嵌套 (子查询)
    - 以一条语句的结果为另一条语句的条件  
```
team
teamID teamName

match
matchID
hostTeamID
guestTeamID
matchTime 
matchResult

//查询06 年 6月 1号 到 7月1号 比赛的结果
```
```
//第一种方式
select 
(select teamName where teamID = a.hostTeamID) a.homeTeam,
a.matchResult,
(select teamName where teamID = a.guestTeamID) guestTeam,
a.matchTime
from match a
where a.matchTime 
between "2016-06-01"  and "2016-07-01";

//第二种方式
select 
t1.teamName,
a.matchResult,
t2.teamName,
a.matchTime
from match a 
LEFT JOIN match t1 on a.hostTeamID = t1.teamID,
LEFT JOIN match t2 on a.guestTeamID = t2.teamID
where a.matchTime 
between "2016-06-01"  and "2016-07-01";
```