
SELECT 
M.field_id_saldo AS ID,
M.field_no_referensi AS REFERENSI,
M.field_tanggal_saldo AS TANGGAL,
M.field_time AS TIMES,
M.field_rekening AS REKENING,
U.field_nama AS NAMA,
M.field_time AS TIMES,
B.field_branch_name AS TRX_CABANG,
M.field_type_saldo AS TIPE,
G.field_sell AS HARGA_EMAS,
G.field_buyback AS BUYBACK,
M.field_kredit_saldo AS KREDIT,
M.field_debit_saldo AS DEBIT,
M.field_total_saldo AS SALDO,
M.field_status AS STATUS
FROM tbltrxmutasisaldo M JOIN tbldeposit D ON M.field_no_referensi=D.field_no_referensi
JOIN tblnasabah N ON N.No_Rekening=M.field_rekening
JOIN tbluserlogin U ON U.field_user_id=N.id_UserLogin
JOIN tblgoldprice G ON M.field_tanggal_saldo=G.field_date_gold
JOIN tblbranch B ON B.field_branch_id=D.field_branch



WHERE M.field_status='S' AND D.field_branch=3172090008
ORDER BY M.field_id_saldo ASC;