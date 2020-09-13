# 未定案

## 規劃

### functional map

[coggle](https://coggle.it/diagram/X0YynsZTxpdAui_r/t/%E5%9D%87%E8%A1%A1%E9%A3%B2%E9%A3%9Fbalanced-diet)

### database dbdiagram 

[dbdiagram](https://dbdiagram.io/d/5f4723bf7b2e2f40e9dee824)


## 實作



part 1.會員 API
- [x]  註冊
- [x]  登入
- [x]  關於我
- [x]  登出
- [x]  忘記密碼
- [x]  修改密碼
- [ ]  修改name
- [ ]  delete user

part 2. etic
- [x]  middleware: 確認 token 有無失效
- [x]  middleware: 紀錄 api
- [x]  gcp deploy

part 3. Restful API bioProfile
- [x] create
- [x] read 
- [X] read by bioProfile id 
- [X] read by user id  
- [X] update
- [x] delete


part 4. Restful API water  
- [x] create
- [x] read summary
- [x] read summary by user id  or filter someday
- [x] update
- [x] delete

*Restful API : diet*
- [x] create by diet type 
       
       - type 0 daily
       - type 1 doctor/standard
- [x] read by kind/standard
- [ ] read detail by user id 
    - 單日多餐 one object
    - 多日 object 
- [ ] read detail by user id  with water summary And  filter someday
- [ ] show
- [ ] update
- [ ] delete
- [ ] compare one day real life with standard



