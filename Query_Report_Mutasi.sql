SELECT 
M.field_id_saldo AS ID,
M.field_no_referensi AS REFERENSI,
M.field_tanggal_saldo AS TANGGAL,
M.field_time AS TIMES,
M.field_rekening AS REKENING,
N.id_UserLogin AS ID_LOGIN,
N.No_Rekening AS REKENING,
U.field_nama AS NAMA,
B.field_branch_name AS CABANG_TERDAFTAR,
M.field_time AS TIMES,
M.field_type_saldo AS TIPE,
M.field_kredit_saldo AS KREDIT,
M.field_debit_saldo AS DEBIT,
M.field_total_saldo AS SALDO,
M.field_status AS STATUS
FROM tbltrxmutasisaldo M 
LEFT JOIN tblnasabah N ON M.field_rekening=N.No_Rekening
LEFT JOIN tbluserlogin U ON N.id_UserLogin=U.field_user_id
LEFT JOIN tblbranch B ON U.field_branch=B.field_branch_id

WHERE M.field_status='S'
ORDER BY M.field_id_saldo ASC;