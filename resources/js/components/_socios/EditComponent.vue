<template>
  <el-tabs type="border-card">
    <el-row :gutter="20">
      <el-col :span="20" style="color: white">.</el-col>
      <el-col :span="4">
        <el-button type="danger" style="width: 100%" @click="regresar()"
          >Regresar</el-button
        >
      </el-col>
    </el-row>
    <el-tab-pane label="Datos del Socio">
      <el-row :gutter="20">
        <el-col :span="6">
          <el-select
            class="w-100"
            v-model="editando.tipo_documento"
            placeholder="Tipo de Documento"
            :filterable="true"
          >
            <el-option
              v-for="item in tipos_documentos"
              :key="item.id"
              :label="item.nombre"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </el-col>
        <el-col :span="6">
          <el-input v-model="editando.documento" placeholder="Documento">
          </el-input>
        </el-col>
        <el-col :span="12">
          <el-input v-model="editando.nombres" placeholder="Nombres">
          </el-input>
        </el-col>
      </el-row>

      <el-row :gutter="20">
        <el-col :span="12">
          <el-input v-model="editando.direccion" placeholder="Direccion">
          </el-input>
        </el-col>
        <el-col :span="6">
          <el-input v-model="editando.telefono" placeholder="Telefono">
          </el-input>
        </el-col>
        <el-col :span="6">
          <el-input v-model="editando.movil" placeholder="Movil"> </el-input>
        </el-col>
      </el-row>

      <el-row :gutter="20">
        <el-col :span="12">
          <el-input v-model="editando.correo" placeholder="Correo"> </el-input>
        </el-col>
        <el-col :span="3" v-if="type_user != 2 && type_user != 4">
          Mies:
          <el-switch v-model="editando.mies"></el-switch>
        </el-col>
        <el-col :span="3" v-if="type_user != 2 && type_user != 4">
          MT:
          <el-switch v-model="editando.mt"></el-switch>
        </el-col>
        <el-col :span="6">
          <el-select
            class="w-100"
            v-model="editando.fuerza.id"
            placeholder="Fuerza"
            :filterable="true"
          >
            <el-option
              v-for="item in fuerzas"
              :key="item.id"
              :label="item.nombre"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :span="12">
          <el-input v-model="editando.observacion" placeholder="Observaciones">
          </el-input>
        </el-col>
        <el-col :span="6">
          <el-select
            class="w-100"
            v-model="editando.estado.id"
            placeholder="Estado"
            :filterable="true"
          >
            <el-option
              v-for="item in estados"
              :key="item.id"
              :label="item.nombre"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </el-col>

        <!-- tipo -->
        <el-col :span="6">
          <el-select
            class="w-100"
            v-model="editando.tipo"
            placeholder="Tipo"
            :filterable="true"
          >
            <el-option
              v-for="item in tipos"
              :key="item.id"
              :label="item.nombre"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </el-col>

        <el-col :span="6">
          <el-upload
            class="upload-demo"
            action="/api/subir-imagen"
            :file-list="fileList"
            :on-success="updatePerfil"
          >
            <el-button size="small" type="primary"
              >Clic para subir Foto del Socio</el-button
            >
            <div slot="tip" class="el-upload__tip">
              Solo archivos jpg/png con un tamaño menor de 500kb
            </div>
          </el-upload>
        </el-col>
      </el-row>

      <el-row :gutter="20">
        <el-col :span="6">
          <el-select
            class="w-100"
            v-model="editando.provincia"
            placeholder="Provincias"
            :filterable="true"
            @change="editando.canton = ''"
          >
            <el-option
              v-for="item in Object.keys(provincias)"
              :key="item"
              :label="item"
              :value="item"
            >
            </el-option>
          </el-select>
        </el-col>
        <el-col :span="6">
          <el-select
            class="w-100"
            v-model="editando.canton"
            placeholder="Canton"
            :filterable="true"
          >
            <el-option
              v-for="item in provincias[editando.provincia]"
              :key="item"
              :label="item"
              :value="item"
            >
            </el-option>
          </el-select>
        </el-col>
      </el-row>
    </el-tab-pane>

    <el-tab-pane label="Datos del Contacto de Emergencia">
      <el-row :gutter="20">
        <el-col :span="12">
          <el-input v-model="editando.nombres_referencia" placeholder="Nombre">
          </el-input>
        </el-col>
        <el-col :span="12">
          <el-input v-model="editando.movil_referencia" placeholder="Movil">
          </el-input>
        </el-col>
      </el-row>
    </el-tab-pane>

    <el-tab-pane
      v-if="type_user == 1 || type_user == 3"
      label="Informacion Financiera"
    >
      <el-row :gutter="20">
        <el-col :span="12">
          <el-input v-model="editando.cuenta" placeholder="Cuenta"> </el-input>
        </el-col>
        <el-col :span="12">
          <el-select
            class="w-100"
            v-model="editando.banco.id"
            placeholder="Banco"
            :filterable="true"
          >
            <el-option
              v-for="item in bancos"
              :key="item.id"
              :label="item.nombre"
              :value="item.id"
            >
              <span style="float: left">{{ item.nombre }}</span>
              <span style="float: right; color: #8492a6; font-size: 13px">{{
                item.siglas
              }}</span>
            </el-option>
          </el-select>
        </el-col>
      </el-row>

      <el-row :gutter="20">
        <!-- hospitalizacion -->
        <el-col :span="12">
          <span class="demo-input-label">Hospitalización</span>
          <el-select
            class="w-100"
            v-model="editando.hospitalizacion"
            placeholder="Hospitalizacion"
            :filterable="true"
          >
            <el-option
              v-for="item in hospitalizacionValores"
              :key="item.id"
              :label="item.nombre"
              :value="item.id"
            >
              <span style="float: left">{{ item.nombre }}</span>
              <span style="float: right; color: #8492a6; font-size: 13px">{{
                item.siglas
              }}</span>
            </el-option>
          </el-select>
        </el-col>
      </el-row>

      <el-row :gutter="20" v-if="type_user == 1 || type_user == 3">
        <el-col :span="12">
          <el-select
            class="w-100"
            v-model="editando.pago.id"
            placeholder="Pago"
            :filterable="true"
          >
            <el-option
              v-for="item in pagos"
              :key="item.id"
              :label="item.nombre"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </el-col>
        <el-col :span="12"> </el-col>
      </el-row>

      <el-row :gutter="20">
        <!-- abonos seleccion -->
        <el-col :span="12">
          <span class="demo-input-label">Abono</span>
          <el-select
            v-model="editando.abono"
            class="w-100"
            placeholder="Abono"
            :filterable="true"
          >
            <el-option
              v-for="item in abonoValores"
              :key="item.id"
              :label="item.nombre"
              :value="item.id"
            >
              <span style="float: left">{{ item.nombre }}</span>
              <span style="float: right; color: #8492a6; font-size: 13px">{{
                item.siglas
              }}</span>
            </el-option>
          </el-select>
        </el-col>
      </el-row>

      <el-row :gutter="20">
        <!-- abonos valores -->
        <el-col :span="12">
          <el-col v-for="(item, index) in abonosValores" :key="index" :span="6">
            <span class="demo-input-label">{{ item.nombre }}</span>
            <el-input v-model="item.valor" :placeholder="item.id"> </el-input>
          </el-col>
        </el-col>
      </el-row>
    </el-tab-pane>

    <el-tab-pane label="Documento del Socio">
      <el-row :gutter="20">
        <el-col :span="12">
          <el-upload
            class="upload-demo"
            action="/api/subir-documento"
            :on-success="updatePdf"
            :file-list="fileList2"
          >
            <el-button size="small" type="primary"
              >Clic para subir Documento</el-button
            >
          </el-upload>
        </el-col>
      </el-row>
    </el-tab-pane>

    <el-row :gutter="20">
      <el-col :span="20" style="color: white">.</el-col>
      <el-col :span="4">
        <el-button type="success" style="width: 100%" @click="updateRegistro()"
          >Guardar</el-button
        >
      </el-col>
    </el-row>
  </el-tabs>
</template>

<script>
  export default {
    props: ["editando"],
    data() {
      return {
        tipos_documentos: [
          { id: 1, nombre: "CEDULA" },
          { id: 2, nombre: "RUC" },
          { id: 3, nombre: "PASAPORTE" },
        ],
        fuerzas: [
          { id: 1, nombre: "EJÉRCITO" },
          { id: 2, nombre: "AEREA" },
          { id: 3, nombre: "NAVAL" },
          { id: 4, nombre: "CIVIL" },
        ],
        estados: [
          { id: 10, nombre: "No existe cuenta" },
          { id: 1, nombre: "Activo" },
          { id: 2, nombre: "Inactivo" },
          { id: 3, nombre: "Bloqueado" },
          { id: 4, nombre: "Fallecido" },
          { id: 5, nombre: "Cuenta cancelada" },
          { id: 6, nombre: "Cuenta inactiva" },
          { id: 7, nombre: "Cuenta no corresponde" },
          { id: 8, nombre: "Expulsado" },
          { id: 9, nombre: "Suspendido" },
        ],
        tipos: [
          { id: 1, nombre: "Militar activo" },
          { id: "2", nombre: "Militar pasivo" },
          { id: 3, nombre: "Policia activo" },
          { id: 4, nombre: "Policia pasivo" },
        ],
        pagos: [
          { id: 1, nombre: "Cancelado" },
          { id: 2, nombre: "Pendiente" },
        ],
        type_user: $("#type_user").val(),
        bancos: [],
        cuentas: [],
        perfil: "",
        pdf: "",
        fileList: [],
        fileList2: [],
        hospitalizacionValores: [
          { id: 1, nombre: "Cancelado" },
          { id: 2, nombre: "Pendiente" },
        ],
        abonoValores: [
          { id: 1, nombre: "Abono" },
          { id: 2, nombre: "Cancelado" },
          { id: 3, nombre: "Pendiente" },
        ],
        abonosValores: [
          { id: "enero", valor: 0, nombre: "Enero" },
          { id: "febrero", valor: 0, nombre: "Febrero" },
          { id: "marzo", valor: 0, nombre: "Marzo" },
          { id: "abril", valor: 0, nombre: "Abril" },
          { id: "mayo", valor: 0, nombre: "Mayo" },
          { id: "junio", valor: 0, nombre: "Junio" },
          { id: "julio", valor: 0, nombre: "Julio" },
          { id: "agosto", valor: 0, nombre: "Agosto" },
          { id: "septiembre", valor: 0, nombre: "Septiembre" },
          { id: "octubre", valor: 0, nombre: "Octubre" },
          { id: "noviembre", valor: 0, nombre: "Noviembre" },
          { id: "diciembre", valor: 0, nombre: "Diciembre" },
        ],
        provincias: {
          Azuay: [
            "Cuenca",
            "Girón",
            "Gualaceo",
            "Nabón",
            "Paute",
            "Pucara",
            "San Fernando",
            "Santa Isabel",
            "Sigsig",
            "Oña",
            "Chordeleg",
            "El Pan",
            "Sevilla de Oro",
            "Guachapala",
            "Camilo Ponce Enríquez",
          ],
          Bolívar: [
            "Guaranda",
            "Chillanes",
            "Chimbo",
            "Echeandía",
            "San Miguel",
            "Caluma",
            "Las Naves",
          ],
          Cañar: [
            "Azogues",
            "Biblián",
            "Cañar",
            "La Troncal",
            "El Tambo",
            "Déleg",
            "Suscal",
          ],
          Carchi: [
            "Tulcán",
            "Bolívar",
            "Espejo",
            "Mira",
            "Montúfar",
            "San Pedro de Huaca",
          ],
          Cotopaxi: [
            "Latacunga",
            "La Maná",
            "Pangua",
            "Pujili",
            "Salcedo",
            "Saquisilí",
            "Sigchos",
          ],
          Chimborazo: [
            "Riobamba",
            "Alausi",
            "Colta",
            "Chambo",
            "Chunchi",
            "Guamote",
            "Guano",
            "Pallatanga",
            "Penipe",
            "Cumandá",
          ],
          "El Oro": [
            "Machala",
            "Arenillas",
            "Atahualpa",
            "Balsas",
            "Chilla",
            "El Guabo",
            "Huaquillas",
            "Marcabelí",
            "Pasaje",
            "Piñas",
            "Portovelo",
            "Santa Rosa",
            "Zaruma",
            "Las Lajas",
          ],
          Esmeraldas: [
            "Esmeraldas",
            "Eloy Alfaro",
            "Muisne",
            "Quinindé",
            "San Lorenzo",
            "Atacames",
            "Rioverde",
            "La Concordia",
          ],
          Guayas: [
            "Guayaquil",
            "Alfredo Baquerizo Moreno (Juján)",
            "Balao",
            "Balzar",
            "Colimes",
            "Daule",
            "Durán",
            "El Empalme",
            "El Triunfo",
            "Milagro",
            "Naranjal",
            "Naranjito",
            "Palestina",
            "Pedro Carbo",
            "Samborondón",
            "Santa Lucía",
            "Salitre (Urbina Jado)",
            "San Jacinto de Yaguachi",
            "Playas",
            "Simón Bolívar",
            "Coronel Marcelino Maridueña",
            "Lomas de Sargentillo",
            "Nobol",
            "General Antonio Elizalde",
            "Isidro Ayora",
          ],
          Imbabura: [
            "Ibarra",
            "Antonio Ante",
            "Cotacachi",
            "Otavalo",
            "Pimampiro",
            "San Miguel de Urcuquí",
          ],
          Loja: [
            "Loja",
            "Calvas",
            "Catamayo",
            "Celica",
            "Chaguarpamba",
            "Espíndola",
            "Gonzanamá",
            "Macará",
            "Paltas",
            "Puyango",
            "Saraguro",
            "Sozoranga",
            "Zapotillo",
            "Pindal",
            "Quilanga",
            "Olmedo",
          ],
          "Los Rios": [
            "Babahoyo",
            "Baba",
            "Montalvo",
            "Puebloviejo",
            "Quevedo",
            "Urdaneta",
            "Ventanas",
            "Vínces",
            "Palenque",
            "Buena Fé",
            "Valencia",
            "Mocache",
            "Quinsaloma",
          ],
          Manabi: [
            "Portoviejo",
            "Bolívar",
            "Chone",
            "El Carmen",
            "Flavio Alfaro",
            "Jipijapa",
            "Junín",
            "Manta",
            "Montecristi",
            "Paján",
            "Pichincha",
            "Rocafuerte",
            "Santa Ana",
            "Sucre",
            "Tosagua",
            "24 de Mayo",
            "Pedernales",
            "Olmedo",
            "Puerto López",
            "Jama",
            "Jaramijó",
            "San Vicente",
          ],
          "Morona Santiago": [
            "Morona",
            "Gualaquiza",
            "Limón Indanza",
            "Palora",
            "Santiago",
            "Sucúa",
            "Huamboya",
            "San Juan Bosco",
            "Taisha",
            "Logroño",
            "Pablo Sexto",
            "Tiwintza",
          ],
          Napo: [
            "Tena",
            "Archidona",
            "El Chaco",
            "Quijos",
            "Carlos Julio Arosemena Tola",
          ],
          Pastaza: ["Pastaza", "Mera", "Santa Clara", "Arajuno"],
          Pichincha: [
            "Quito",
            "Cayambe",
            "Mejia",
            "Pedro Moncayo",
            "Rumiñahui",
            "San Miguel de Los Bancos",
            "Pedro Vicente Maldonado",
            "Puerto Quito",
          ],
          Tungurahua: [
            "Ambato",
            "Baños de Agua Santa",
            "Cevallos",
            "Mocha",
            "Patate",
            "Quero",
            "San Pedro de Pelileo",
            "Santiago de Píllaro",
            "Tisaleo",
          ],
          "Zamora Chinchipe": [
            "Zamora",
            "Chinchipe",
            "Nangaritza",
            "Yacuambi",
            "Yantzaza (Yanzatza)",
            "El Pangui",
            "Centinela del Cóndor",
            "Palanda",
            "Paquisha",
          ],
          Galápagos: ["San Cristóbal", "Isabela", "Santa Cruz"],
          Sucumbíos: [
            "Lago Agrio",
            "Gonzalo Pizarro",
            "Putumayo",
            "Shushufindi",
            "Sucumbíos",
            "Cascales",
            "Cuyabeno",
          ],
          Orellana: ["Orellana", "Aguarico", "La Joya de Los Sachas", "Loreto"],
          "Santo Domingo de Los Tsáchilas": ["Santo Domingo"],
          "Santa Elena": ["Santa Elena", "La Libertad", "Salinas"],
          "Zonas No Delimitadas": [
            "Las Golondrinas",
            "Manga del Cura",
            "El Piedrero",
          ],
        },
      };
    },
    mounted() {
      axios.get("/api/planes-cuenta").then((response) => {
        this.cuentas = response.data;
      });
      axios.get("/api/bancos").then((response) => {
        this.bancos = response.data;
        console.log(this.bancos);
      });
      let countIndex = 0;
      JSON.parse(this.editando.abonos).forEach((element) => {
        this.abonosValores[countIndex].valor = element;
        countIndex++;
      });
      this.editando.abono = parseInt(this.editando.abono);
      this.editando.tipo = this.editando.tipo.toString();
    },

    methods: {
      updateRegistro() {
        const params = {
          banco: this.editando.banco,
          cuenta: this.editando.cuenta,
          tipo_documento: this.editando.tipo_documento,
          documento: this.editando.documento,
          nombres: this.editando.nombres,
          direccion: this.editando.direccion,
          telefono: this.editando.telefono,
          movil: this.editando.movil,
          correo: this.editando.correo,
          nombre_referencia: this.editando.nombres_referencia,
          movil_referencia: this.editando.movil_referencia,
          observacion: this.editando.observacion,
          perfil: this.editando.perfil,
          pdf: this.editando.pdf,
          fuerza: this.editando.fuerza,
          estado: this.editando.estado,
          tipo: this.editando.tipo,
          mies: this.editando.mies,
          mt: this.editando.mt,
          id: this.editando.id,
          pago: this.editando.pago,
          hospitalizacion: this.editando.hospitalizacion,
          abono: this.editando.abono,
          abonos: JSON.stringify([
            this.abonosValores[0].valor,
            this.abonosValores[1].valor,
            this.abonosValores[2].valor,
            this.abonosValores[3].valor,
            this.abonosValores[4].valor,
            this.abonosValores[5].valor,
            this.abonosValores[6].valor,
            this.abonosValores[7].valor,
            this.abonosValores[8].valor,
            this.abonosValores[9].valor,
            this.abonosValores[10].valor,
            this.abonosValores[11].valor,
          ]),
          provincia: this.editando.provincia,
          canton: this.editando.canton,
        };

        axios.put("/api/socios", params).then((response) => {
          console.log(response.data);

          this.$notify.success({
            title: "Procesado!",
            message: "Socio Editado Correctamente",
            offset: 100,
          });

          this.$emit("new");
        });
      },

      regresar() {
        this.$emit("regresar");
      },
      updatePdf(res, file) {
        this.editando.pdf = res;
        console.log(this.pdf);
      },
      updatePerfil(res, file) {
        this.editando.perfil = res;
        console.log(this.perfil);
      },
    },
  };
</script>
