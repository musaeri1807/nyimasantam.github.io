/*
SELECT DISTINCT(field_rekening),(SELECT S1.field_total_saldo FROM tbltrxmutasisaldo S1 WHERE S1.field_rekening = S2.field_rekening AND S1.field_status='S' ORDER BY S1.field_id_saldo DESC LIMIT 1)  
AS SALDO,U.field_nama AS NAMA,U.field_member_id AS MEMBERID,B.field_branch_name AS BRANCH, U.field_user_id AS ID 
            FROM tbltrxmutasisaldo S2 
            JOIN tbluserlogin U ON S2.field_member_id = U.field_member_id
            JOIN tblnasabah N ON U.field_user_id=N.id_UserLogin
            JOIN tblbranch B ON U.field_branch=B.field_branch_id
            ORDER BY S2.field_id_saldo DESC;



SELECT * FROM tbltrxmutasisaldo WHERE field_type_saldo='200' ORDER BY field_id_saldo DESC;

SELECT * FROM tbltrxmutasisaldo  ORDER BY field_id_saldo DESC;
SELECT * FROM tblwithdraw ;
SELECT * FROM tblwithdrawdetail ;
SELECT * FROM tbldeposit;
SELECT * FROM tbldepositdetail;



TRUNCATE tblwithdraw;
TRUNCATE tblwithdrawdetail;

TRUNCATE tbldeposit;
TRUNCATE tbldepositdetail;



SELECT EL.field_name_officer,AM.field_role_id,M.field_menu,SM.field_submenu FROM tblmenusub SM JOIN tblmenu M ON SM.field_idmenu=M.field_idmenu
JOIN tblemployeaccessmenu AM ON SM.field_idmenu=AM.field_idmenu local_vpsbsp
JOIN tblemployeeslogin EL ON EL.field_role=AM.field_role_id  ORDER BY EL.field_role ASC;
*/

										SELECT    T.field_trx_deposit AS ID,
                                        T.field_deposit_id,
                                        P.field_product_name AS PRODUK,
                                        K.field_name_category AS KATEGORI,
                                        I.field_date_deposit AS TANGGAL,
                                        I.field_no_referensi AS REFERENSI,
                                        I.field_rekening_deposit AS REKENING,
                                        N.No_Rekening,
                                        U.field_branch AS IDNB_CABANG,
                                        UB.field_branch_name AS NB_CABANG,
                                        U.field_nama AS NAMA,
                                        I.field_branch AS TRX_CABANG,
                                        B.field_branch_name AS CABANG,
                                        T.field_price_product AS HARGA,
                                        T.field_quantity AS QTY,
                                        T.field_total_price AS TOTAL,
                                        I.field_operation_fee AS 5PERSEN,
                                        T.field_total_price/100*5 AS RESULT_PERSEN,
                                        T.field_total_price-T.field_total_price/100*5 AS DEPO,                                      
                                        (T.field_total_price-T.field_total_price/100*5)/I.field_gold_price AS GOLD,                                    
                                        I.field_gold_price AS HARGA_EMAS,
                                        E.field_name_officer AS PETUGAS,                                          
                                        E.field_role
                                        FROM tbldepositdetail T JOIN tblproduct P ON  T.field_product=P.field_product_id
                                        JOIN tblcategory K ON P.field_category=K.field_category_id
                                        JOIN tbldeposit I ON T.field_trx_deposit=I.field_trx_deposit 
                                        JOIN tblnasabah N ON I.field_rekening_deposit=N.No_Rekening
                                        JOIN tblbranch B ON I.field_branch=B.field_branch_id 
                                        JOIN tbluserlogin U ON N.id_UserLogin=U.field_user_id
                                        JOIN tblemployeeslogin E ON I.field_officer_id=E.field_user_id                                   
                                        JOIN tblbranch UB ON U.field_branch=UB.field_branch_id 
 													 
                                        ORDER BY T.field_deposit_id ASC;
	