<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Init extends CI_Migration {

    function up() {
        
        $sql = <<<SQL

CREATE TABLE m_komoku
(
    komoku_id character(6) NOT NULL,
    kubun character(3) NOT NULL,
    komoku_name_1 character varying(100) NOT NULL,
    komoku_name_2 text,
    komoku_name_3 text,
    "use" integer,
    "sort" integer,
    note1 text,
    note2 text,
    create_user character(10),
    edit_user character(10),
    create_date timestamp without time zone,
    edit_date timestamp without time zone,
    del_flg character(1) DEFAULT '0' NOT NULL,
    CONSTRAINT t_komoku_pkey PRIMARY KEY (komoku_id, kubun)
);

CREATE TABLE m_employee
(
    employee_id character(10) NOT NULL,
    first_name character varying(50) NOT NULL,
    last_name character varying(50) NOT NULL,
    first_name_kana character varying(50),
    last_name_kana character varying(50),
    "password" character varying(200) NOT NULL,
    pw_update_date date,
    login_time timestamp without time zone,
    permission_id character(3),
    department character(3),
    position character(3),
    classify character(3),
    gender character varying(10),
    birthday date,
    "address" text,
    phone character varying(30),
    tel character varying(30),
    email_job character varying(50),
    email_personal character varying(50),
    postal_code character varying(20),
    "status" character(3),
    active_flg character(1),
    admin_flg character(1),
    entry_date date,
    retire_date date,
    theme integer DEFAULT 1,
    note text,
    icon text,
    create_user character(10),
    edit_user character(10),
    create_date timestamp without time zone,
    edit_date timestamp without time zone,
    del_flg character(1) DEFAULT '0' NOT NULL,
    CONSTRAINT m_employee_pkey PRIMARY KEY (employee_id)
);

CREATE TABLE m_company
(
    company_id serial,
    company_name character varying(100),
    short_name character varying(30),
    "type" character(1), -- 1: customer, 2: partner
    contract_type character(3), -- contract print type
    reference character varying(20),
    head_office_name character varying(100),
    head_office_address text,
    head_office_phone character varying(30),
    head_office_tel character varying(30),
    head_office_fax character varying(30),
    head_office_contract_name character varying(50),
    branch_name character varying(100),
    branch_address text,
    branch_phone character varying(30),
    transportation character varying(50), --delivery_term
    fee_term character(3),
    vat_by character(3),
    bank_info text,
    payment_term character(3),
    contract_from_date date,
    contract_end_date date,
	contract_end_flg character(1),
    note text,
    items_list text,
    create_user character(10),
    edit_user character(10),
    create_date timestamp without time zone,
    edit_date timestamp without time zone,
    del_flg character(1) DEFAULT '0' NOT NULL,
    CONSTRAINT m_company_pkey PRIMARY KEY (company_id)
);

CREATE TABLE m_items
(
    item_code character varying(50) NOT NULL,
	jp_code character varying(50),
	customer_code character(5) NOT NULL,
    item_name character varying(200),
    item_name_vn character varying(200),
    item_name_com character varying(200),
    item_name_dsk character varying(200),
    item_name_des character varying(200),
    composition_1 character varying(200), -- item of description 1
    composition_2 character varying(200), -- item of description 2
    composition_3 character varying(200), -- item of description 3
    end_of_sales character(1) DEFAULT '0' NOT NULL,
    salesman character varying(30)  NOT NULL,
    customer character varying(150),
    apparel character varying(100),
    unit character varying(10),
    size_unit character varying(10),
    "size" character varying(30),
    currency character varying(10),
    vendor character varying(100),
    vendor_parts character varying(100),
    color character varying(30),
    vendor_color character varying(100),
    lot_quantity real,
	quantity real,
    moq real, -- minimun quantity
    net_wt real,
    buy_price_vnd real,
    buy_price_usd real,
    buy_price_jpy real,
    sell_price_vnd real,
    sell_price_usd real,
    sell_price_jpy real,
    base_price_vnd real,
    base_price_usd real,
    base_price_jpy real,
    shosha_price_vnd real,
    shosha_price_usd real,
    shosha_price_jpy real,
	selfobject_code character varying(30),
    inspection_rate text,
    origin character varying(30),
	note text,
    note_po_sheet text,
    note_lapdip text,
    create_user character(10),
    edit_user character(10),
	create_date timestamp without time zone,
    edit_date timestamp without time zone,
    del_flg character(1) DEFAULT '0' NOT NULL,
    CONSTRAINT m_items_pkey PRIMARY KEY (item_code, jp_code, customer_code, item_name, item_name_vn, customer, salesman, size, color)
);

CREATE TABLE t_orders_receive
(
    order_receive_no character(50) NOT NULL,
    partition_no integer NOT NULL,
    order_receive_date date NOT NULL,
    kubun character(1), --1: shimada japan, 2: other
    seller_kb character(1), --1: EPE, 2: HANOI
    input_user character(10),
    identify_name character varying(30),
    customer character varying(150),
    delivery_to character varying(150),
    delivery_address text,
    head_office character varying(150),
    head_office_address text,
    bank character varying(50),
    currency character varying(10),
    rate_usd real,
    rate_jpy real,
    rate_jpy_usd real,
    payment character varying(50),
    sum_quantity real,
    sum_amount real,
    sum_amount_base real,
    style character varying(20),
    contract_no character(20),
    apparel character varying(20),
    tax character varying(20),
    delivery_date date,
    odr_department character varying(20),
    assistance character varying(20),
    staff character varying(20),
    attention character varying(20),
    "status" character(3),
    accpt_flg character(1),
    inv_print_date date,
    contract_print_date date,
    wish_inspect_date date,
    wish_delivery_date date,
    wish_packing_date date,
    plan_inspect_date date,
    plan_delivery_date date,
    plan_packing_date date,
    payment_date date,
    note text,
    payment_term text,
    delivery_term character varying(50), --transportation
    fee_term text,
    vat_by text,
    create_user character(10),
    edit_user character(10),
    create_date timestamp without time zone,
    edit_date timestamp without time zone,
    del_flg character(1) DEFAULT '0' NOT NULL,
    CONSTRAINT t_orders_receive_pkey PRIMARY KEY (order_receive_no, partition_no, order_receive_date)
);

CREATE TABLE t_orders_receive_details
(
    order_receive_no character(50) NOT NULL,
    partition_no integer NOT NULL,
    order_receive_date date NOT NULL,
    order_receive_detail_no integer NOT NULL,
    jp_code character varying(50),
    item_code character varying(50) NOT NULL,
    item_name character varying(200),
    composition_1 character varying(200), -- item of description 1
    composition_2 character varying(200), -- item of description 2
    composition_3 character varying(200), -- item of description 3
    odr_recv_quantity real,
    size_unit character varying(20),
    "size" character varying(30),
    vendor character varying(100),
    inv_no text,
	color character varying(30),
	vendor_color character varying(30),
    unit character(10),
    quantity real,
    hikiate_quantity real,
    buy_price real,
    sell_price real,
    base_price real,
    amount real,
    amount_base real,
    delivery_date date,
    inspect_date date,
    inspect_user character(10),
    "status" character(3),
    create_user character(10),
    edit_user character(10),
    create_date timestamp without time zone,
    edit_date timestamp without time zone,
    del_flg character(1) DEFAULT '0' NOT NULL,
    CONSTRAINT t_orders_receive_details_pkey PRIMARY KEY (order_receive_no, partition_no, order_receive_date, order_receive_detail_no)
);

CREATE TABLE t_store_item
(
    salesman character varying(30),
    jp_code character varying(50),
    item_code character varying(50) NOT NULL,
    item_type character(3) NOT NULL,
    order_no character(30),
    order_detail_no integer,
    invoice_no character varying(30),
    warehouse character varying(30),
    item_name character varying(200),
    composition_1 character varying(200), -- item of description 1
    composition_2 character varying(200), -- item of description 2
    composition_3 character varying(200), -- item of description 3
    size_unit character varying(10),
    size character varying(30),
    vendor character varying(100),
    vendor_parts character varying(100),
    color character varying(30),
    vendor_color character varying(30),
    brand character(10),
    apparel character(20),
    unit character(10),
    quantity real,
    arrival_ok real,
    arrival_ng real,
    inspect_ok real,
    inspect_ng real,
    buy_price real,
    sales_price real,
    note text,
    buy_amount real,
    "status" character(3),
    arrival_status character(3),
    arrival_user character(10),
    arrival_date timestamp without time zone,
    arrival_note text,
    inspect_status character(3),
    inspect_user character(10),
    inspect_date timestamp without time zone,
    inspect_note text,
    inspect_note_path text,
    keep_user character(10),
    keep_date timestamp without time zone,
    sm_change_user character(10),
    sm_change_date timestamp without time zone,
    order_receive_no character(50),
    partition_no integer,
    odr_recv_date date,
    odr_recv_detail_no integer,
    order_user character(10),
    create_user character(10),
    edit_user character(10),
    create_date timestamp without time zone,
    edit_date timestamp without time zone,
    del_flg character(1) DEFAULT '0' NOT NULL,
	CONSTRAINT t_store_item_pkey PRIMARY KEY (salesman, item_code, item_type,order_receive_no, partition_no, order_no, order_detail_no, warehouse, size, color)
);

CREATE TABLE t_store_item_his
(
    id serial NOT NULL,
    salesman character varying(30),
    jp_code character varying(50),
    item_code character (50) NOT NULL,
    item_type character(3) NOT NULL,
    order_no character(30),
    order_detail_no integer,
    invoice_no character varying(30),
    warehouse character varying(30),
    size character varying(30),
    vendor character varying(100),
    color character varying(30),
    unit character(10),
    quantity real,
    arrival_ok real,
    arrival_ng real,
    inspect_ok real,
    inspect_ng real,
    buy_price real,
    sales_price real,
    note text,
    arrival_user character(10),
    arrival_date timestamp without time zone,
    inspect_user character(10),
    inspect_date timestamp without time zone,
    keep_user character(10),
    keep_date timestamp without time zone,
    sm_change_user character(10),
    sm_change_date timestamp without time zone,
    order_receive_no character(50),
    partition_no integer,
    odr_recv_date date,
    odr_recv_detail_no integer,
    order_user character(10),
    create_user character(10),
    create_date timestamp without time zone,
	CONSTRAINT t_store_item_his_pkey PRIMARY KEY (id)
);

CREATE TABLE t_orders
(
	order_no_1 character(2) NOT NULL,
	order_no_2 character(4) NOT NULL,
	order_no_3 character(1) NOT NULL,
	order_no_4 integer NOT NULL,
	order_no_5 integer NOT NULL,
	order_no_6 character(5),
    buyer_kb character(1) NOT NULL, --1: EPE, 2: HANOI
    tax character varying(20),
	invoice_no character varying(30),
    contract_no character(20),
    order_date date NOT NULL,
    quantity real,
    supplier_name character varying(100),
    delivery_company character varying(100),
	"address" text,
    note text,
    shipping_mark text,
	amount real,
	currency character varying(10),
    payment character varying(50),
    customs_clearance_sheet_no character varying(50),
    customs_clearance_fee real,
    transport_fee real,
    apparel character varying(20),
    delivery_date date,
    revise_date date,
    reference character varying(20),
    delivery_plan_date date,
    order_user character(10),
    apply_user character(10),
	apply_date timestamp without time zone,
    accept_user character(10),
	accept_date timestamp without time zone,
    denial_user character(10),
	denial_date timestamp without time zone,
	po_sheet_date date,
    sales_infor character varying(100),
    "status" character(3),
    create_user character(10),
    edit_user character(10),
    create_date timestamp without time zone,
    edit_date timestamp without time zone,
    del_flg character(1) DEFAULT '0' NOT NULL,
    CONSTRAINT t_orders_pkey PRIMARY KEY (order_no_1, order_no_2, order_no_3, order_no_4, order_no_5, buyer_kb, order_date)
);

CREATE TABLE t_orders_details
(
	order_no_1 character(2) NOT NULL,
	order_no_2 character(4) NOT NULL,
	order_no_3 character(1) NOT NULL,
	order_no_4 integer NOT NULL,
	order_no_5 integer NOT NULL,
	order_no_6 character(5),
    buyer_kb character(1) NOT NULL, --1: EPE, 2: HANOI
	order_date date NOT NULL,
    order_detail_no integer NOT NULL,
    item_type character(3) NOT NULL,
    warehouse character varying(30),
    odr_recv_no character(50),
	partition_no integer,
	odr_recv_date date,
	odr_recv_detail_no integer,
    item_code character varying(50),
	item_name character varying(200),
    composition_1 character varying(200), -- item of description 1
    composition_2 character varying(200), -- item of description 2
    composition_3 character varying(200), -- item of description 3
    odr_quantity real,
	size_unit character varying(10),
	"size" character varying(30),
	vendor character varying(100),
	vendor_parts character varying(100),
    color character varying(30),
	vendor_color character varying(30),
	unit character varying(10),
    price real,
    amount real,
	note text,
    surcharge_po text,
    "status" character(3),
    salesman character varying(30), -- staff or filnal customer
    create_user character(10),
    edit_user character(10),
    create_date timestamp without time zone,
    edit_date timestamp without time zone,
    del_flg character(1) DEFAULT '0' NOT NULL,
    CONSTRAINT t_orders_details_pkey PRIMARY KEY (order_no_1, order_no_2, order_no_3, order_no_4, order_no_5, order_date, buyer_kb, order_detail_no)
);

CREATE TABLE t_dvt 
(
    order_date date NOT NULL,
	delivery_require_date date,
	dvt_no character varying(50) NULL,
	"times" integer NOT NULL,
    kubun character(1), --1: shimada japan, 2: other
	delivery_method character varying(30),
	staff character varying(20),
	staff_id character varying(10),
	assistance character varying(20),
	department character varying(20),
	factory character varying(50),
	"address" text,
	po_infor text,
    inv_flg character(1),
    currency character varying(10),
    "status" character(3),
	note text,
	factory_require_date date,
	factory_plan_date date,
	delivery_plan_date date,
	pv_infor text,
	pv_in_date date,
	packing_date date,
    measurement_date date,
	passage_date date,
	factory_delivery_date date,
	knq_delivery_date date,
	knq_fac_deli_date date,
	salesman character varying(30),
    case_mark text,
    case_mark_text text,
	print_date date,
    payment_date date,
    buyer character varying(150),
	create_user character(10),
    edit_user character(10),
    create_date timestamp without time zone,
    edit_date timestamp without time zone,
    del_flg character(1) DEFAULT '0' NOT NULL,
    CONSTRAINT t_dvt_pkey PRIMARY KEY (order_date, dvt_no, "times")
);

CREATE TABLE t_kvt
(
    order_date date NOT NULL,
	dvt_no character varying(50) NOT NULL,
	"times" integer NOT NULL,
	kvt_no character varying(50) NOT NULL,
    detail_no integer NOT NULL,
	staff character varying(20),
	staff_id character varying(10),
	assistance character varying(20),
	department character varying(20),
	factory character varying(50),
	"address" text,
	stype_no character varying(20),
	o_no character varying(20),
	item_jp_code character varying(50),
	item_code character varying(50),
	item_name character varying(200),
    composition_1 character varying(200), -- item of description 1
    composition_2 character varying(200), -- item of description 2
    composition_3 character varying(200), -- item of description 3
	color character varying(30),
	size character varying(30),
    unit character varying(10),
    size_unit character varying(10),
	quantity real,
    buy_price real,
    sell_price real,
    base_price real,
    shosha_price real,
	contract_no character(30),
	delivery_date date,
	packing_date date,
	delivery_method character varying(30),
	pv_no text,
	po_delivery_date date,
	po_quantity real,
	print_date date,
    "status" character(3),
    inv_no text,
    case_mark text,
	note text,
    create_user character(10),
    edit_user character(10),
    create_date timestamp without time zone,
    edit_date timestamp without time zone,
    del_flg character(1) DEFAULT '0' NOT NULL,
    CONSTRAINT t_kvt_pkey PRIMARY KEY (order_date, dvt_no, "times", kvt_no, detail_no, item_code, color, size)
);

CREATE TABLE t_packing 
(
    pack_no serial NOT NULL,
	packing_date date,
    invoice_no character varying(30),
    packages integer,
    types character varying(100),
	quantity real,
	netwt real,
	grosswt real,
	measure real,
    delivery_method character varying(30),
	customer character varying(150),
    delry_from character varying(150),
    delry_from_add text,
    delry_to character varying(150),
    delry_to_add text,
	excel_print_date date,
	pdf_print_date date,
    apply_user character(10),
	apply_date timestamp without time zone,
    measurement_user character(10),
	measurement_date timestamp without time zone,
    accept_user character(10),
	accept_date timestamp without time zone,
	"status" character(3),
    case_mark text,
    note text,
    create_user character(10),
    edit_user character(10),
    create_date timestamp without time zone,
    edit_date timestamp without time zone,
    del_flg character(1) DEFAULT '0' NOT NULL,
    CONSTRAINT t_packing_pkey PRIMARY KEY (pack_no)
);

CREATE TABLE t_packing_details 
(
    pack_no serial NOT NULL,
    packing_details integer NOT NULL,
    order_date date,
	dvt_no character varying(50),
    "times" integer,
	kvt_no character varying(50),
	package_type character(3),
	number_from integer,
    number_to integer,
	details_no integer,
    item_code character varying(50),
    jp_code character varying(50),
    item_name character varying(200),
    composition_1 character varying(200), -- item of description 1
    composition_2 character varying(200), -- item of description 2
    composition_3 character varying(200), -- item of description 3
    color character varying(30),
    size character varying(30),
    unit character varying(10),
    size_unit character varying(10),
    quantity_detail real,
    multiple integer,
	quantity real,
	netwt real,
	grosswt real,
	measure real,
    lot_no character varying(30),
	inv_no text,
	note text,
    create_user character(10),
    edit_user character(10),
    create_date timestamp without time zone,
    edit_date timestamp without time zone,
    del_flg character(1) DEFAULT '0' NOT NULL,
    CONSTRAINT t_packing_details_pkey PRIMARY KEY (pack_no, packing_details)
);

CREATE TABLE m_company_branch 
(
    company_id serial,
    branch_id character(3) NOT NULL,
	branch_name character varying(100),
	branch_address text,
    branch_address_vn text,
    branch_phone character varying(50),
    branch_tel character varying(50),
    branch_fax character varying(50),
    branch_contract_name character varying(100),
    branch_position character varying(100),
    branch_transportation character varying(50), --delivery_term
    branch_tax_code character varying(20),
	contract_from_date date,
	contract_end_date date,
	contract_end_flg character(1),
	note text,
    create_user character(10),
    edit_user character(10),
    create_date timestamp without time zone,
    edit_date timestamp without time zone,
    del_flg character(1) DEFAULT '0' NOT NULL,
    CONSTRAINT m_company_branch_pkey PRIMARY KEY (company_id, branch_id)
);

CREATE TABLE m_company_headoffice 
(
    company_id serial,
    head_office_id character(3) NOT NULL,
	head_office_name character varying(100),
	head_office_address text,
    head_office_address_vn text,
    head_office_phone character varying(50),
    head_office_tel character varying(50),
    head_office_fax character varying(50),
    head_office_contract_name character varying(100),
    head_office_position character varying(100),
    head_transportation character varying(50), --delivery_term
    head_office_tax_code character varying(20),
	contract_from_date date,
	contract_end_date date,
	contract_end_flg character(1),
	note text,
    create_user character(10),
    edit_user character(10),
    create_date timestamp without time zone,
    edit_date timestamp without time zone,
    del_flg character(1) DEFAULT '0' NOT NULL,
    CONSTRAINT m_company_headoffice_pkey PRIMARY KEY (company_id, head_office_id)
);

CREATE TABLE m_company_shipper
(
    company_id integer,
    shipper_id character(3) NOT NULL,
	shipper_name character varying(100),
	shipper_address text,
    shipper_phone character varying(50),
    shipper_tel character varying(50),
    shipper_fax character varying(50),
    shipper_contract_name character varying(100),
	contract_from_date date,
	contract_end_date date,
	contract_end_flg character(1),
	note text,
    create_user character(10),
    edit_user character(10),
    create_date timestamp without time zone,
    edit_date timestamp without time zone,
    del_flg character(1) DEFAULT '0' NOT NULL,
    CONSTRAINT m_company_shipper_pkey PRIMARY KEY (company_id, shipper_id)
);

-- CREATE TABLE t_konpo 
-- (
--     delivery_no character(10)  NOT NULL,
--     konpo_no character(10),
--     "status" character(3),
--     case_mark character varying(100),
-- 	print_date date,
--     note character varying(100),
--     create_user character(10),
--     edit_user character(10),
--     create_date timestamp without time zone,
--     edit_date timestamp without time zone,
--     del_flg character(1) DEFAULT '0' NOT NULL,
--     CONSTRAINT t_konpo_pkey PRIMARY KEY (delivery_no)
-- );
-- CREATE TABLE t_delivery 
-- (
--     order_receive_no character(10)  NOT NULL,
--     partition_no integer NOT NULL,
--     order_receive_date date  NOT NULL,
--     delivery_date date,
--     delivery_no character(10),
--     konpo_no character(10),
--     saleman character(10),
--     "status" character(3),
--     customer character varying(30),
--     delivery_to character varying(30),
--     "address" character varying(30),
--     contract_no character varying(30),
-- 	print_date date,
--     note character varying(100),
--     create_user character(10),
--     edit_user character(10),
--     create_date timestamp without time zone,
--     edit_date timestamp without time zone,
--     del_flg character(1) DEFAULT '0' NOT NULL,
--     CONSTRAINT t_delivery_pkey PRIMARY KEY (order_receive_no, partition_no, order_receive_date)
-- );

CREATE TABLE t_print
(
    delivery_no character varying(50) NOT NULL,
    inventory_voucher_excel_no character(20),
    "times" integer NOT NULL,
    delivery_date date NOT NULL,
    pack_no serial NOT NULL,
    packing_date date NOT NULL,
    print_date timestamp without time zone,
    print_user character(10),
    invoice_no character varying(30),
    red_invoice_no character varying(30),
    customer_code character(5),
    payment character(3),
    other_reference character varying(50),
    data_currency character varying(10),
    print_currency character varying(10),
    rate real,
    rate_jpy real,
    rate_jpy_usd real,
    packages integer,
    quantity real,
    netwt real,
    grosswt real,
    measure real,
    seller character varying(150),
    seller_add text,
    buyer character varying(150),
    buyer_add text,
    customer character varying(150),
    consignee text,
    notify character varying(150),
    notify_add text,
    "from" text,
    "to" text,
    invoice_flg character(1),
    packinglist_flg character(1),
    delivery_note_flg character(1),
    delivery_voucher_flg character(1),
    delivery_voucher_excel_flg character(1),
    "status" character(3),
    case_mark text,
    vessel_flight character varying(30),
    payment_term character varying(150),
    delivery_condition character varying(150),
    note text,
    contract_no character varying(30),
    create_user character(10),
    edit_user character(10),
    create_date timestamp without time zone,
    edit_date timestamp without time zone,
    del_flg character(1) DEFAULT '0' NOT NULL,
    CONSTRAINT t_print_pkey PRIMARY KEY (delivery_no, "times", delivery_date, pack_no, packing_date)
);

CREATE TABLE t_po_print
(
    po_no character(23) NOT NULL,
    order_date date NOT NULL,
    "times" integer NOT NULL DEFAULT 0,
    print_date timestamp without time zone,
    print_user character(10),
    header character(3),
    consignee character(3),
    insurance character varying(10),
    freight character varying(10),
    note_detail text,
    payment_term text,
    pv_no text,
    transportation text,
    shipper text,
    note character(1),
    create_user character(10),
    edit_user character(10),
    create_date timestamp without time zone,
    edit_date timestamp without time zone,
    del_flg character(1) DEFAULT 0,
    CONSTRAINT t_po_print_pkey PRIMARY KEY (po_no, order_date)
);

CREATE TABLE t_contract_print
(
  contract_no character varying(30) NOT NULL,
  agreement_contract_no character varying(30),
  kubun character(4), -- La kubun cua contractype trong komoku. Them 1(nhap),2(xuat) vao phia truoc de phan biet nhap va xuat, agreement la 004
  delivery_no character varying(50) NOT NULL,
  "times" integer NOT NULL,
  pack_no integer NOT NULL,
  contract_date date,
  end_date date,
  delivery_date date,
  delivery_date_eachtime date,
  term_delivery character varying(50),
  agreement_no character varying(20),
  bank character(3),
  party_A character varying(150),
  party_B character varying(150),
  notify character varying(150),
  consignee character varying(150),
  party_charged character varying(10),
  delivery_condition character varying(50),
  agreement_date date,
  "signature" boolean,
  vat text,
  payment_currency character(3),
  payment_methods text,
  payment_term text,
  terms_overdue boolean,
  scan_signature boolean,
  feedback_day_num integer,
  fee_terms text,
  rate real,
  rate_jpy real,
  rate_jpy_usd real,
  quantity_odds real,
  payment_date date,
  reference character varying(20),
  official_delivery_date date,
  customs_clearance_fee real,
  transport_fee real,
  "status" character(3),
  receipt_type text,
  submit_contract_date date,
  note text,
  create_user character(10),
  edit_user character(10),
  create_date timestamp without time zone,
  edit_date timestamp without time zone,
  del_flg character(1) DEFAULT 0,
  CONSTRAINT t_contract_print_pkey PRIMARY KEY (contract_no, kubun, delivery_no, "times", delivery_date, pack_no)
);

CREATE TABLE m_surcharge
(
  id serial NOT NULL,
  item_code character varying(50) NOT NULL,
  size character varying(30),
  size_unit character varying(30),
  color character varying(30),
  qty_by_color_from integer,
  qty_by_color_to integer,
  qty_by_order integer,
  po_amount_min_usd real,
  po_amount_min_vnd real,
  po_amount_min_jpy real,
  surcharge_unit_color_usd real,
  surcharge_unit_color_vnd real,
  surcharge_unit_color_jpy real,
  surcharge_color_usd real,
  surcharge_color_vnd real,
  surcharge_color_jpy real,
  surcharge_po text,
  create_user character(10),
  edit_user character(10),
  create_date timestamp without time zone,
  edit_date timestamp without time zone,
  del_flg character(1) DEFAULT 0,
  CONSTRAINT m_surcharge_pkey PRIMARY KEY (id)
);
-- Function: log_history_store_item()

-- DROP FUNCTION log_history_store_item();

CREATE OR REPLACE FUNCTION log_history_store_item()
  RETURNS trigger AS
$$
DECLARE
    reason t_store_item_his.note%TYPE;
BEGIN
	IF TG_OP = 'INSERT' THEN
		reason := 'Add new item';
	ELSIF TG_OP = 'UPDATE' THEN
		--insert to history table when change this columns
		IF OLD.salesman IS NOT DISTINCT FROM NEW.salesman
			AND OLD.item_code IS NOT DISTINCT FROM NEW.item_code
			AND OLD.item_type IS NOT DISTINCT FROM NEW.item_type
			AND OLD.order_no IS NOT DISTINCT FROM NEW.order_no
			AND OLD.order_detail_no IS NOT DISTINCT FROM NEW.order_detail_no
			AND OLD.warehouse IS NOT DISTINCT FROM NEW.warehouse
			AND OLD.size IS NOT DISTINCT FROM NEW.size
			AND OLD.color IS NOT DISTINCT FROM NEW.color
			AND OLD.quantity IS NOT DISTINCT FROM NEW.quantity
			AND (OLD.arrival_ok IS NOT DISTINCT FROM NEW.arrival_ok OR (OLD.arrival_ok IS NULL AND NEW.arrival_ok=0))
			AND (OLD.arrival_ng IS NOT DISTINCT FROM NEW.arrival_ng OR (OLD.arrival_ng IS NULL AND NEW.arrival_ng=0))
			AND (OLD.inspect_ok IS NOT DISTINCT FROM NEW.inspect_ok OR (OLD.inspect_ok IS NULL AND NEW.inspect_ok=0))
			AND (OLD.inspect_ng IS NOT DISTINCT FROM NEW.inspect_ng OR (OLD.inspect_ng IS NULL AND NEW.inspect_ng=0))
			AND OLD.order_receive_no IS NOT DISTINCT FROM NEW.order_receive_no
			AND OLD.partition_no IS NOT DISTINCT FROM NEW.partition_no THEN
			RETURN NEW;
		END IF;
		--Set reason
		reason := '';
		IF OLD.arrival_ok IS DISTINCT FROM NEW.arrival_ok THEN
			IF reason <> '' THEN
				reason := reason || ' - ';
			END IF;
			reason := reason || 'Arrival';
		END IF;
		IF OLD.arrival_ng IS DISTINCT FROM NEW.arrival_ng THEN 
			IF reason <> '' THEN
				reason := reason || ' - ';
			END IF;
			reason := reason || 'Inspect';
		END IF;
		IF OLD.inspect_ok IS DISTINCT FROM NEW.inspect_ok THEN
			IF reason <> '' THEN
				reason := reason || ' - ';
			END IF;
			reason := reason || 'OK';
		END IF;
		IF OLD.inspect_ng IS DISTINCT FROM NEW.inspect_ng THEN
			IF reason <> '' THEN
				reason := reason || ' - ';
			END IF;
			reason := reason || 'NG';
		END IF;
		IF reason <> '' THEN
			reason := 'Change ' || reason || ' quantity';
		END IF;
		IF NEW.note <> '' and reason <> '' THEN
			reason := reason || ' to ' || NEW.note;
		END IF;
		IF right(NEW.note,8) = 'Cho xuat' THEN
			reason := 'Allocate to ' || NEW.note;
		END IF;
		
		--Only update primary key
		--Insert OLD item
		IF OLD.salesman IS DISTINCT FROM NEW.salesman 
			OR OLD.item_type IS DISTINCT FROM NEW.item_type 
			OR OLD.warehouse IS DISTINCT FROM NEW.warehouse THEN
			--Insert new data
			INSERT INTO t_store_item_his(
			    salesman, jp_code, item_code, item_type, order_no, order_detail_no, 
			    invoice_no, warehouse, size, vendor, color, unit, quantity, arrival_ok, 
			    arrival_ng, inspect_ok, inspect_ng, buy_price, sales_price, 
			    arrival_user, arrival_date, inspect_user, inspect_date, keep_user, 
			    keep_date, sm_change_user, sm_change_date, order_receive_no, 
			    partition_no, odr_recv_date, odr_recv_detail_no, order_user, 
			    create_user, create_date, note)
			VALUES (OLD.salesman, OLD.jp_code, OLD.item_code, OLD.item_type, OLD.order_no, OLD.order_detail_no,
			    OLD.invoice_no, OLD.warehouse, OLD.size, OLD.vendor, OLD.color, OLD.unit, OLD.quantity, OLD.arrival_ok,
			    OLD.arrival_ng, OLD.inspect_ok, OLD.inspect_ng, OLD.buy_price, OLD.sales_price,
			    OLD.arrival_user, OLD.arrival_date, OLD.inspect_user, OLD.inspect_date, OLD.keep_user,
			    OLD.keep_date, OLD.sm_change_user, OLD.sm_change_date, OLD.order_receive_no,
			    OLD.partition_no, OLD.odr_recv_date, OLD.odr_recv_detail_no, OLD.order_user,
			    OLD.edit_user, now(), reason);
		END IF;
	END IF;
	--Insert new data
	INSERT INTO t_store_item_his(
		salesman, jp_code, item_code, item_type, order_no, order_detail_no, 
		invoice_no, warehouse, size, vendor, color, unit, quantity, arrival_ok, 
		arrival_ng, inspect_ok, inspect_ng, buy_price, sales_price, 
		arrival_user, arrival_date, inspect_user, inspect_date, keep_user, 
		keep_date, sm_change_user, sm_change_date, order_receive_no, 
		partition_no, odr_recv_date, odr_recv_detail_no, order_user, 
		create_user, create_date, note)
	VALUES (NEW.salesman, NEW.jp_code, NEW.item_code, NEW.item_type, NEW.order_no, NEW.order_detail_no,
		NEW.invoice_no, NEW.warehouse, NEW.size, NEW.vendor, NEW.color, NEW.unit, NEW.quantity, NEW.arrival_ok,
		NEW.arrival_ng, NEW.inspect_ok, NEW.inspect_ng, NEW.buy_price, NEW.sales_price,
		NEW.arrival_user, NEW.arrival_date, NEW.inspect_user, NEW.inspect_date, NEW.keep_user,
		NEW.keep_date, NEW.sm_change_user, NEW.sm_change_date, NEW.order_receive_no,
		NEW.partition_no, NEW.odr_recv_date, NEW.odr_recv_detail_no, NEW.order_user,
		NEW.edit_user, now(), reason);
	RETURN NEW;
END;
$$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION log_history_store_item()
  OWNER TO postgres;

  -- Trigger: store_item_changes on t_store_item

-- DROP TRIGGER store_item_changes ON t_store_item;

DROP TRIGGER IF EXISTS store_item_changes on "public"."t_store_item";
CREATE TRIGGER store_item_changes
  AFTER INSERT OR UPDATE
  ON t_store_item
  FOR EACH ROW
  EXECUTE PROCEDURE log_history_store_item();

SQL;
        $this->db->query($sql);
    }

    function down() {
        $sql = <<<SQL
DROP SCHEMA public CASCADE;
CREATE SCHEMA public;
SQL;
        echo $this->db->query($sql);
    }
}