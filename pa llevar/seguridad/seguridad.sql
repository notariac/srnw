PGDMP         6        	        p         	   seguridad    8.4.4    8.4.4 5    (           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                       false            )           0    0 
   STDSTRINGS 
   STDSTRINGS     )   SET standard_conforming_strings = 'off';
                       false            *           1262    969047 	   seguridad    DATABASE     y   CREATE DATABASE seguridad WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'es_PE.utf8' LC_CTYPE = 'es_PE.utf8';
    DROP DATABASE seguridad;
             postgres    false                        2615    2200    public    SCHEMA        CREATE SCHEMA public;
    DROP SCHEMA public;
             postgres    false            +           0    0    SCHEMA public    COMMENT     6   COMMENT ON SCHEMA public IS 'standard public schema';
                  postgres    false    6            ,           0    0    public    ACL     �   REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;
                  postgres    false    6            I           2612    16386    plpgsql    PROCEDURAL LANGUAGE     $   CREATE PROCEDURAL LANGUAGE plpgsql;
 "   DROP PROCEDURAL LANGUAGE plpgsql;
             postgres    false            2           1247    969048    dni    DOMAIN     4   CREATE DOMAIN dni AS character varying(8) NOT NULL;
    DROP DOMAIN public.dni;
       public       postgres    false    6            3           1247    969049    estado    DOMAIN     4   CREATE DOMAIN estado AS integer NOT NULL DEFAULT 1;
    DROP DOMAIN public.estado;
       public       postgres    false    6            4           1247    969050    fechareg    DOMAIN     7   CREATE DOMAIN fechareg AS date NOT NULL DEFAULT now();
    DROP DOMAIN public.fechareg;
       public       postgres    false    6                        1255    2499558    f_listarmodulos()    FUNCTION     e  CREATE FUNCTION f_listarmodulos() RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
DECLARE
	r RECORD;
BEGIN
	FOR r IN
		SELECT m.idmodulo, m.descripcion, p.descripcion, s.descripcion
		FROM modulos m 
		INNER JOIN sistemas s ON s.idsistema = m.idsistema
		INNER JOIN modulos p ON p.idmodulo = m.idpadre
	LOOP
		RETURN NEXT r;
	END LOOP;
RETURN;
END;
$$;
 (   DROP FUNCTION public.f_listarmodulos();
       public       postgres    false    329    6            �           1259    969060    perfiles    TABLE     s   CREATE TABLE perfiles (
    idperfil integer NOT NULL,
    descripcion character varying(80),
    estado estado
);
    DROP TABLE public.perfiles;
       public         postgres    true    6    307                        1255    2499537    f_listarperfiles()    FUNCTION     �   CREATE FUNCTION f_listarperfiles() RETURNS SETOF perfiles
    LANGUAGE plpgsql
    AS $$
DECLARE
	r RECORD;
BEGIN
	FOR r IN
		SELECT idperfil as codigo, descripcion, estado FROM perfiles
	LOOP
		RETURN NEXT r;
	END LOOP;
RETURN;
END;
$$;
 )   DROP FUNCTION public.f_listarperfiles();
       public       postgres    false    329    6    314                        1255    2499550    f_listarsistemas()    FUNCTION     �   CREATE FUNCTION f_listarsistemas() RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
DECLARE
	r RECORD;
BEGIN
	FOR r IN
		SELECT idsistema, descripcion, path, estado FROM sistemas
	LOOP
		RETURN NEXT r;
	END LOOP;
RETURN;
END;
$$;
 )   DROP FUNCTION public.f_listarsistemas();
       public       postgres    false    6    329                        1255    2499559    f_listarusuarios()    FUNCTION     �   CREATE FUNCTION f_listarusuarios() RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
DECLARE
	r RECORD;
BEGIN
	FOR r IN
		SELECT idusuario, dni, nombres, telefonos, login FROM usuario
	LOOP
		RETURN NEXT r;
	END LOOP;
RETURN;
END;
$$;
 )   DROP FUNCTION public.f_listarusuarios();
       public       postgres    false    329    6            �           1259    969051    modulos    TABLE       CREATE TABLE modulos (
    idmodulo integer NOT NULL,
    idsistema integer DEFAULT 4 NOT NULL,
    descripcion character varying(100),
    url character varying(80),
    idpadre integer,
    orden integer,
    imagen character varying(100),
    estado estado,
    fechareg fechareg
);
    DROP TABLE public.modulos;
       public         postgres    false    1804    6    308    307            �           1259    969055    modulos_idmodulo_seq    SEQUENCE     v   CREATE SEQUENCE modulos_idmodulo_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
 +   DROP SEQUENCE public.modulos_idmodulo_seq;
       public       postgres    false    6    1516            -           0    0    modulos_idmodulo_seq    SEQUENCE OWNED BY     ?   ALTER SEQUENCE modulos_idmodulo_seq OWNED BY modulos.idmodulo;
            public       postgres    false    1517            .           0    0    modulos_idmodulo_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('modulos_idmodulo_seq', 194, true);
            public       postgres    false    1517            �           1259    969057    modulos_perfil    TABLE     c   CREATE TABLE modulos_perfil (
    idmodulo integer,
    idsistema integer,
    idperfil integer
);
 "   DROP TABLE public.modulos_perfil;
       public         postgres    true    6            �           1259    969063    perfiles_idperfil_seq    SEQUENCE     w   CREATE SEQUENCE perfiles_idperfil_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
 ,   DROP SEQUENCE public.perfiles_idperfil_seq;
       public       postgres    false    1519    6            /           0    0    perfiles_idperfil_seq    SEQUENCE OWNED BY     A   ALTER SEQUENCE perfiles_idperfil_seq OWNED BY perfiles.idperfil;
            public       postgres    false    1520            0           0    0    perfiles_idperfil_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('perfiles_idperfil_seq', 7, true);
            public       postgres    false    1520            �           1259    969065    sistema_perfiles    TABLE     b   CREATE TABLE sistema_perfiles (
    idsistema integer,
    idperfil integer,
    estado estado
);
 $   DROP TABLE public.sistema_perfiles;
       public         postgres    true    6    307            �           1259    969068    sistemas    TABLE     �   CREATE TABLE sistemas (
    idsistema integer NOT NULL,
    descripcion character varying(80),
    path character varying(100),
    estado estado,
    referencia character varying(500),
    imagen character varying(100)
);
    DROP TABLE public.sistemas;
       public         postgres    true    6    307            �           1259    969074    sistemas_idsistema_seq    SEQUENCE     x   CREATE SEQUENCE sistemas_idsistema_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
 -   DROP SEQUENCE public.sistemas_idsistema_seq;
       public       postgres    false    1522    6            1           0    0    sistemas_idsistema_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE sistemas_idsistema_seq OWNED BY sistemas.idsistema;
            public       postgres    false    1523            2           0    0    sistemas_idsistema_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('sistemas_idsistema_seq', 5, true);
            public       postgres    false    1523            �           1259    969076    usuarios_idusuario_seq    SEQUENCE     {   CREATE SEQUENCE usuarios_idusuario_seq
    START WITH 113
    INCREMENT BY 1
    MAXVALUE 9999
    MINVALUE 0
    CACHE 1;
 -   DROP SEQUENCE public.usuarios_idusuario_seq;
       public       postgres    false    6            3           0    0    usuarios_idusuario_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('usuarios_idusuario_seq', 114, true);
            public       postgres    false    1524            �           1259    969078    usuario    TABLE     �  CREATE TABLE usuario (
    dni dni NOT NULL,
    idusuario integer DEFAULT nextval('usuarios_idusuario_seq'::regclass) NOT NULL,
    idperfil integer,
    login character varying(40),
    contra character varying(40),
    nombres character varying(80),
    direccion character varying(200),
    telefonos character varying(40),
    observacion character varying(255),
    fechaingreso date,
    estado estado,
    fechareg fechareg
);
    DROP TABLE public.usuario;
       public         postgres    true    1808    308    306    6    307            �           1259    969085    usuario_sistemas    TABLE     �   CREATE TABLE usuario_sistemas (
    idusuario integer,
    idsistema integer,
    estado estado,
    idperfil integer,
    administrador integer DEFAULT 0
);
 $   DROP TABLE public.usuario_sistemas;
       public         postgres    true    1809    307    6                       2604    969088    idmodulo    DEFAULT     a   ALTER TABLE modulos ALTER COLUMN idmodulo SET DEFAULT nextval('modulos_idmodulo_seq'::regclass);
 ?   ALTER TABLE public.modulos ALTER COLUMN idmodulo DROP DEFAULT;
       public       postgres    false    1517    1516                       2604    969089    idperfil    DEFAULT     c   ALTER TABLE perfiles ALTER COLUMN idperfil SET DEFAULT nextval('perfiles_idperfil_seq'::regclass);
 @   ALTER TABLE public.perfiles ALTER COLUMN idperfil DROP DEFAULT;
       public       postgres    false    1520    1519                       2604    969090 	   idsistema    DEFAULT     e   ALTER TABLE sistemas ALTER COLUMN idsistema SET DEFAULT nextval('sistemas_idsistema_seq'::regclass);
 A   ALTER TABLE public.sistemas ALTER COLUMN idsistema DROP DEFAULT;
       public       postgres    false    1523    1522            !          0    969051    modulos 
   TABLE DATA               k   COPY modulos (idmodulo, idsistema, descripcion, url, idpadre, orden, imagen, estado, fechareg) FROM stdin;
    public       postgres    false    1516   J=       "          0    969057    modulos_perfil 
   TABLE DATA               @   COPY modulos_perfil (idmodulo, idsistema, idperfil) FROM stdin;
    public       postgres    false    1518   �F       #          0    969060    perfiles 
   TABLE DATA               :   COPY perfiles (idperfil, descripcion, estado) FROM stdin;
    public       postgres    false    1519   H       $          0    969065    sistema_perfiles 
   TABLE DATA               @   COPY sistema_perfiles (idsistema, idperfil, estado) FROM stdin;
    public       postgres    false    1521   �H       %          0    969068    sistemas 
   TABLE DATA               U   COPY sistemas (idsistema, descripcion, path, estado, referencia, imagen) FROM stdin;
    public       postgres    false    1522   �H       &          0    969078    usuario 
   TABLE DATA               �   COPY usuario (dni, idusuario, idperfil, login, contra, nombres, direccion, telefonos, observacion, fechaingreso, estado, fechareg) FROM stdin;
    public       postgres    false    1525   J       '          0    969085    usuario_sistemas 
   TABLE DATA               Z   COPY usuario_sistemas (idusuario, idsistema, estado, idperfil, administrador) FROM stdin;
    public       postgres    false    1526   X                  2606    969377    perfiles_pkey 
   CONSTRAINT     S   ALTER TABLE ONLY perfiles
    ADD CONSTRAINT perfiles_pkey PRIMARY KEY (idperfil);
 @   ALTER TABLE ONLY public.perfiles DROP CONSTRAINT perfiles_pkey;
       public         postgres    false    1519    1519                       2606    969379 
   pk_modulos 
   CONSTRAINT     Z   ALTER TABLE ONLY modulos
    ADD CONSTRAINT pk_modulos PRIMARY KEY (idmodulo, idsistema);
 <   ALTER TABLE ONLY public.modulos DROP CONSTRAINT pk_modulos;
       public         postgres    false    1516    1516    1516                       2606    969381 
   pk_usuario 
   CONSTRAINT     P   ALTER TABLE ONLY usuario
    ADD CONSTRAINT pk_usuario PRIMARY KEY (idusuario);
 <   ALTER TABLE ONLY public.usuario DROP CONSTRAINT pk_usuario;
       public         postgres    false    1525    1525                       2606    969383    sistemas_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY sistemas
    ADD CONSTRAINT sistemas_pkey PRIMARY KEY (idsistema);
 @   ALTER TABLE ONLY public.sistemas DROP CONSTRAINT sistemas_pkey;
       public         postgres    false    1522    1522                       2606    969384    fk_modperfil_perfiles    FK CONSTRAINT        ALTER TABLE ONLY modulos_perfil
    ADD CONSTRAINT fk_modperfil_perfiles FOREIGN KEY (idperfil) REFERENCES perfiles(idperfil);
 N   ALTER TABLE ONLY public.modulos_perfil DROP CONSTRAINT fk_modperfil_perfiles;
       public       postgres    false    1519    1518    1812                       2606    969389    fk_modulos_sistemas    FK CONSTRAINT     x   ALTER TABLE ONLY modulos
    ADD CONSTRAINT fk_modulos_sistemas FOREIGN KEY (idsistema) REFERENCES sistemas(idsistema);
 E   ALTER TABLE ONLY public.modulos DROP CONSTRAINT fk_modulos_sistemas;
       public       postgres    false    1814    1522    1516                       2606    969394    fk_sistemas    FK CONSTRAINT     �   ALTER TABLE ONLY usuario_sistemas
    ADD CONSTRAINT fk_sistemas FOREIGN KEY (idsistema) REFERENCES sistemas(idsistema) ON DELETE CASCADE;
 F   ALTER TABLE ONLY public.usuario_sistemas DROP CONSTRAINT fk_sistemas;
       public       postgres    false    1522    1814    1526                       2606    969399    fk_sistperfiles_sistemas    FK CONSTRAINT     �   ALTER TABLE ONLY sistema_perfiles
    ADD CONSTRAINT fk_sistperfiles_sistemas FOREIGN KEY (idsistema) REFERENCES sistemas(idsistema);
 S   ALTER TABLE ONLY public.sistema_perfiles DROP CONSTRAINT fk_sistperfiles_sistemas;
       public       postgres    false    1522    1814    1521                       2606    969404    fk_sistperil_perfiles    FK CONSTRAINT     �   ALTER TABLE ONLY sistema_perfiles
    ADD CONSTRAINT fk_sistperil_perfiles FOREIGN KEY (idperfil) REFERENCES perfiles(idperfil);
 P   ALTER TABLE ONLY public.sistema_perfiles DROP CONSTRAINT fk_sistperil_perfiles;
       public       postgres    false    1521    1812    1519                       2606    969409 
   fk_usuario    FK CONSTRAINT     n   ALTER TABLE ONLY usuario
    ADD CONSTRAINT fk_usuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);
 <   ALTER TABLE ONLY public.usuario DROP CONSTRAINT fk_usuario;
       public       postgres    false    1525    1816    1525                        2606    969414 
   fk_usuario    FK CONSTRAINT     �   ALTER TABLE ONLY usuario_sistemas
    ADD CONSTRAINT fk_usuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario) ON DELETE CASCADE;
 E   ALTER TABLE ONLY public.usuario_sistemas DROP CONSTRAINT fk_usuario;
       public       postgres    false    1526    1816    1525            !   l	  x��Y]o�F}ƿ��l>�!.��X����d;�6 ��������]�m�+�����q��f�h��I� \�Sכ�~�xk'V�-���yn��]��9/���O�G�����2S��:���:twA�]���R�8D��'ℛW����n�XQ����Āg[/z�/�nè�]9��)?������s�է�2rm�&<ka(�.�9�J�-ؽ��u^
�� �X�õ�I�����MB���J�l~L��-��]������9������ՙj��bb��b�m<�*A/z���i�՚X� N�a��E�_��{b�a����"���	5�������CV��u���6�-�_�f��k^s"��u�N��.��Zo���>�������᫳Lv�3��K0	�L���$1}���t�U���/����5�et$}�Q@U D�;˥�Sg,���5�������N}�x�0�/
*/;�u�
���|��͋S96�GBESׁ�O�N�L�	[7�:������W��%����Λ�O�&�e`�c)C.I@T̂ȃ��E�m���r $@՞i�������8�M����@�,k�7(W��`�z��O�s�Y���W/��Q��N��W��)k0��JO��D�L����J�"�L�p��v,�a��{Ų[��+׬IG�Lta��^�F�P^�����e"��p���/ە�JaF��Y��d`]g�AH�4���\0Ԝi*@�c\o�m\�)Xz̪����½�Q�	�T��<���._ЧH�&`	��!���5`- �U5k@=�H2�6�~�6��>�u\dL�':JϦF�i㹢{�e�%�j
>�>�RXp.�-I����R�Z(n���Ʋ	�=�ԩ�e4�#S�( �y�W�����7�
� X�P��n�>RN��x!�?�*[���n���@���T�&�<�����#�T2�0�87�K<0z�[y-��豍9���Q��/9�$�@�u������G� [�Ք;��B���_��:  /P[����σ�aaA��{��.٭��WR��N�8|�i}���&Lڤ�8v����6�\ϙ�c�D
�꼸�!��W璍�捱ZV�7`������0J�X��ϊO�_�eV�� �~�q��<�&fil�j���l�uFE�M�WfH�mz��4�P�>��_y!�SPs�X����Jk@1un_)���*�4	�T���Z�����ע����cv�o"��a��v�B )��#{j�{U�j�MW.��m�E+���9"��/��G��W��/y�!ӏ5S���As��[& �E˟��0�����͒ c܋/��):�Y�Ln�G�Ε�>����&�g/^F>��X��ZԱ����Pz�\�P�X��������튔�>�!��¤��~�.����m: $VF#�ޠ0${��� �l��i&7�G���Ng=Z�N�# ��6/8:1aS^����l��E@Tw�r����ٱ���}���ߦ��G̅��)}H�Ws77&m$����x�v<!4"�&7�6:��\ˎ��'���X�Q'�M�^��@��A��]v�[�\a5��j?K��e:�ҳ�l	z�6��y�c�Dd#C9��U�9��11�@�e"���ir�Ѓ�a^��e�h3͚:��z��/�"�6A���̉���+�� �h@�l���Ҋ攱��霟$�J��^R��G�5�sJ�>�!�8$��.+m~�6#Р,VyTs@�(��m������X�2�+K��x{2�)��3���P��!�*���.p&8���Aʦe
d�)�M�?�!�a�|i×��	������#'0QIt��D���#	��A��K�C �G�1q���o^0���0��E��]���s!�J	{�^L�������x���a����*�����`:�� ����$�aH{@���(/#'�j�b��[9���4f<��~�1AB'X�R��Η���;Q'�3�!�,������!f�����=N�Q��6��}U��蟱�GP��FO*�`���F�a���t�s�Lf�[���ܮ!��~q6K���iq��;�D�6н���z��
�28��1vþ~���dw [`�?.%pc߅�d���@Dm�vŽ��+�dвe�yŉ�Q��AN�~!C����z�L��=�����'�E���#׈)�K����F:h͸��3H$k�v�.�#v�6�Q�H({xt.�mib&��C>U���{QJ@�`Z�?dU&O]����}��M<jL��!r��*.�|�Ikx��_?,��;KD��V��QDM��g�_��?�đ�'[BG����������*��g��YD� ��Jo���P�wTH��4�L�t��r      "   7  x�5��q�0�v1g���눣[}t���1V^q�{���qՎtX��h�imT��B�O��S�}�>5<���G��5~$bT�'����򓯻� [j�\[^r��#�x8�������=Ŏr�:�j���H����\#'e�%�*��s��B��9&��=���ғIO&i.�\��������O�zy�X�xP�b�s��O9��}�gLɘ��-N?��Y&�=N{�x� �2B�R<�@�!�F��*�W�
�HxE�	�a|`�^��+�����b�E[��R����F6{� ��6�A5�����}��M��      #   k   x��I1��+��a=��AF�N��x�u���9u-�Faׇ�U���u��9x���5F�׷C��.]I�Ac��Sz	,� O��y�޵�n|��5H�      $   (   x�3�4�4�2��P�L�I0i
&���9������ ���      %   O  x�m��J�0��ӧ8O��9E��i(���I� �dM�:f;�ѧ���(��$�9����"��!!�yި"��\w�56!)�xŨ�i�\ׅ��.�2Ƶ
BjЅ�yI5���*YSUPA�����)>]t}AU<G��g)<�G2���/��
����%���`�QQ�P���xg�y��/�?C��J�1A����7��w��̡�2�뮟f?�'���!(fo�fo�����h��l�];�h|좛tI��$�y>>$Iz���ۻ8�³NލoMۏß�cì�U���p��5��;��*WaeL�4&�a6�A��Yf|��(�0��      &   �  x��Z�r�6}��U~���E��}IA$,AC�
/�ȕ��2�̸2g=���/�)������ x�-9�u<"є� ����f�HI��̏X�^��R�Ł�P�oua�F�l�2Uu�xQ��ۛ�P\umUV[ŷ7OZ.�?`�ޥ]z���`�q�1!�]�޾>>�3!}��0�����u�ʼ♪+��5����X���<��K~�{��ĥ_��WdR,0L�[S�㛟�>0E�w?=��?0�JS��R����;N@�EJ��R0,ݪ�s�)n!Y�ڴ���T7��k��bU�b�Hx��V*��D�X����Aa� `�M�ֵ�pZ�����(^��q'�Ub��5%��h�T̕"J�(Y��v�� еSW%�㱌X(� ��)���CLc�(`�OS�98�:�aN�3����?��My�j�������ƹ�=,G���X9Ij���*��5S�+��:��=$���Ezg�g����4�,I���*u�������U�3�V�Y�M�)^v[���#A����δ�t~��"�8{|�$"����L�z��w��]�ʴ�ڦķq����O�@$U�������j}(uaW!e���>_��.�^����v�z��uu5��ɗ݈/U��Ç�u��塐O�N]��h@�`AL�V�3�����{	BV]w5�ՈXݜ�i�45��Z:�D$b`^b1c�	~���P��M�.�����C��j��z�җv��T��2W���{�M��c���X� �VWymV0�� �`��P�QʡK��D��2մ�������Z-k�ƿ�K�C#N�a�Ĳ5Y�B��jd'��"G\��b�Æ��K9���B� �}�qS��B�N۷;RݪkZ8�/4ҕ�>�dچgې�u�0�1${�dQ���e�m5a`oJ�e:.c��,�!qQ�x�3S�Vz�Ҹ��!ڑ5�ji�SzJ��J	���*�7�.6�����9bPQ�Ү���p��j]�(���-xa�p*J��Fi������2fݙ�Y��c����l��^x��K	;���Y��*��G饗��;h�6�%2����������Ǫ��`t�4͎.��<��ީ[i�����������^8q z�Ch.PQ��ZsV�'!]�*)�=oծC�n����Ne��ԏO��.C�1�/ Ih�se��Ъ��{a^eH��[�P��G�*��J����8:-�sB�ޚ�'t�+�e�pv+XU[�1ž�u�����jE7�A�?x!�<���g����q�t�K�����$Фc�ArZ���}!�(d1P�Պ�Ā�����O�sWK]FH�<��8�(���؛뢪����
��6ԭ�M�h^꠸p�}"z�����b�1Ax����o��F�}��F!�@�P4�j�l<�ϕŋ @<�p��y�S�]�b Z��lmp�Obho��";��%���p��?{<Ͼb�}/a(��\"W�}�����
Ǎ�ib��T�4��HNH��Hpň�6�x���{!��^��V��㖄�B�b�L�PZ�]�#�N��+�A��H,!P3�����B�!ؙ&�X�:�|��Pp����	�B�,Kb�jٮ������l�w2*-��+�?�lsE5A/�%������A����b�L��P�%���R�lW���(��Ah�� i�N-���Y������OyɌ\y	�&�i�C���ơ��nls0%s���ct�S�[��{�Ƈ��J��VD��\7hYW�Z��P�c�b6���Bbud��/GY>��NU��l��g��좰ɪ�$��K�Z򢽒v=`T�`L�k;������RkL�g��
.�'�^>$f�qXqO��p�B^J1n��)�ˈ�tЅ6+|��q�cɹY:�ҩ����o4�4h��B($<�r]Sm9�������0qD:!�>�@0��4�d�I��I@E� �F,k�����s��` D�b^D��u�$E�9���ӹ�"���Rx���
yt�5����t�V���l�\���R��+�����Km�bR?��L��lb�=se�����߁$S���5U��X�0)����h��N�;��&7�Jf���ᇉ�gX~8�t�=�D��Y@ͺLSQ�_�e��xsI�B�9>i!���uAq���_A0�Ȧ��U�Br�Qł�ǩ��q��N�k���kX��*�7��V���	�K�!��CK�:ccۖ��E��
����$��`�k��h�7`� ��ҰE�vBw�Xި�X��h�V%����un��r����&!B�@��:���{�"16������˩�6�F'E4�VR/a`Q�b"އ�I�SB\��}C��uق������C��\�'���`l,f��1{��B��C�d%�%�'����8!�����*#���	a�xh�L�tHr��X�f(-�v
j����@LS%�~h*;E{�5���A"�\Zm�:%4��)A�y�C�iy^J�OD�F@S�Y�u�q��6��Km�u���e���KEe(��U��V]�o�ž�3���kL�!����u0d��j���1zO��%�Ƅ~����8
$3��Z��X{6U1\y/��aV�fh5�=�ȼ�e_T��&+x��s�6�7]n�!�C��PQ��m\ �_�]^������B|!�FX�{���DH6*W�����E���$!���D8����_��x ��Ǽ�^����F�U�B�o�"��b{2�=Z<хM����������`j�;^`�%E��-UH|b�����*,�R��������Db�ԴtNܟ�$|.�����Q���d��l�O��mO�qΘ�%��U����f�(�ޅ�C,�}W���U�k��/�{ͬ�
F�td	@� ^!쫑�!��G؛�~|�������c�������_ߏ��1����PM�i�C���'_���^����ww��|��}����&��lR	��$ �>�-��l$��h^]5c������߫���B�f%��a,�I���ڀ�Qj�������-��Z�R>��/]PH�)�K1�K��b d�:�6�FW�}����:~���7?��������Ñ�\�O�9��ǟ��y0����@݀�;��Ln0��������p����o�-lz����xL��t�r�� 8I�CoD٦5�q�T��pܒ�z�2�eT1>o,���4�S�
�c_}�݇��#&�&��!|�ޥI@ӬUTtWW�y%X�P��[���s"3ED��%Ez37��eԤ�_��3 á�}�??$��I$�X�����7������>�N�p����h[���m{tv/�d�E�Q$#�ƃ�Ř��`V�,��k3�L�o��V캑�NE�p�×����u�X��W��*z�X�9���^��Q�Ga��{<!-�������-��w��ʏ����}���x���n?��?�����؈�ğ��a%�����b�?�54�      '   +   x�344�4�4A.C ��m��6��*���͠�\1z\\\ ��
x     