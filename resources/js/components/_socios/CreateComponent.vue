<!-- @format -->
<template>
  <div class="card">
    <form-wizard
      scope="props"
      nextButtonText="Siguiente"
      backButtonText="Atrás"
      finishButtonText="Guardar"
      @on-complete="newRegistro"
    >
      <h2 slot="title">Registro de Socio</h2>
      <tab-content title="Datos del Socio">
        <el-row :gutter="20">
          <el-col :span="6">
            <el-select
              class="w-100"
              v-model="tipo_documento"
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
            <el-input v-model="documento" placeholder="Documento"> </el-input>
          </el-col>
          <el-col :span="12">
            <el-input v-model="nombres" placeholder="Nombres"> </el-input>
          </el-col>
        </el-row>

        <el-row :gutter="20">
          <el-col :span="12">
            <el-input v-model="direccion" placeholder="Direccion"> </el-input>
          </el-col>
          <el-col :span="6">
            <el-input v-model="telefono" placeholder="Telefono"> </el-input>
          </el-col>
          <el-col :span="6">
            <el-input v-model="movil" placeholder="Movil"> </el-input>
          </el-col>
        </el-row>

        <el-row :gutter="20">
          <el-col :span="12">
            <el-input v-model="correo" placeholder="Correo"> </el-input>
          </el-col>
          <el-col :span="3" v-if="type_user == 1 || type_user == 3">
            Mies:
            <el-switch v-model="mies"></el-switch>
          </el-col>
          <el-col :span="3" v-if="type_user == 1 || type_user == 3">
            MT:
            <el-switch v-model="mt"></el-switch>
          </el-col>
          <el-col :span="6">
            <el-select
              class="w-100"
              v-model="fuerza"
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
            <el-input v-model="observaciones" placeholder="Observaciones">
            </el-input>
          </el-col>
          <el-col :span="6">
            <el-select
              class="w-100"
              v-model="estado"
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
              v-model="tipo"
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
      </tab-content>

      <tab-content title="Datos del Contacto de Emergencia">
        <el-row :gutter="20">
          <el-col :span="12">
            <el-input v-model="nombre_referencia" placeholder="Nombre">
            </el-input>
          </el-col>
          <el-col :span="12">
            <el-input v-model="movil_referencia" placeholder="Movil">
            </el-input>
          </el-col>
        </el-row>
      </tab-content>

      <tab-content title="Informacion Financiera">
        <el-row :gutter="20">
          <el-col :span="12">
            <el-input v-model="cuenta" placeholder="Cuenta"> </el-input>
          </el-col>
          <el-col :span="12">
            <el-select
              class="w-100"
              v-model="banco"
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
              v-model="hospitalizacion"
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

        <el-row :gutter="20">
          <!-- abonos seleccion -->
          <el-col :span="12">
            <span class="demo-input-label">Abono</span>
            <el-select
              v-model="abono"
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
            <el-col
              v-for="(item, index) in abonosValores"
              :key="index"
              :span="6"
            >
              <span class="demo-input-label">{{ item.nombre }}</span>
              <el-input v-model="item.valor" :placeholder="item.id"> </el-input>
            </el-col>
          </el-col>
        </el-row>
      </tab-content>

      <tab-content title="Documento del Socio">
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
      </tab-content>
    </form-wizard>
  </div>
</template>

<style>
  .el-row {
    margin-bottom: 20px;
  }
</style>

<script>
  import { required, email, minLength, sameAs } from "vuelidate/lib/validators";
  export default {
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
          { id: 2, nombre: "Militar pasivo" },
          { id: 3, nombre: "Policia activo" },
          { id: 4, nombre: "Policica pasivo" },
        ],
        fuerza: "",
        mies: false,
        mt: false,
        bancos: [],
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
        banco: "",
        hospitalizacion: "",
        abono: "",
        cuenta: "",
        tipo_documento: "",
        documento: "",
        nombres: "",
        direccion: "",
        telefono: "",
        movil: "",
        correo: "",
        nombre_referencia: "",
        movil_referencia: "",
        observaciones: "",
        fileList: [],
        fileList2: [],
        perfil: "",
        pdf: "",
        estado: "",
        tipo: 1,
        type_user: $("#type_user").val(),
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

      axios.get("/api/get-user-auth").then((response) => {
        console.log(response.data);
      });
    },

    methods: {
      newRegistro() {
        const params = {
          banco: this.banco,
          cuenta: this.cuenta,
          tipo_documento: this.tipo_documento,
          documento: this.documento,
          nombres: this.nombres,
          direccion: this.direccion,
          telefono: this.telefono,
          movil: this.movil,
          correo: this.correo,
          nombre_referencia: this.nombre_referencia,
          movil_referencia: this.movil_referencia,
          observaciones: this.observaciones,
          perfil: this.perfil,
          pdf: this.pdf,
          fuerza: this.fuerza,
          estado: this.estado,
          tipo: this.tipo,
          mies: this.mies,
          mt: this.mt,
          user_id: $("#usuario_id").val(),
          hospitalizacion: this.hospitalizacion,
          abono: this.abono,
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
        };

        // console.log(params);

        axios.post("/api/socios", params).then((response) => {
          console.log(response.data);
          if (response.data == "existe") {
            this.$notify.error({
              title: "Error!",
              message: "Este socio ya existe!",
              offset: 100,
            });
          } else if (response.data == "Campo foto del socio esta vacio") {
            this.$notify.error({
              title: "Error!",
              message: response.data,
              offset: 100,
            });
          } else {
            this.$notify.success({
              title: "Procesado!",
              message: "Registro Creado Correctamente",
              offset: 100,
            });
            this.$emit("new");
          }
        });
      },
      cargar() {
        console.log("Cargando");
      },
      updatePdf(res, file) {
        this.pdf = res;
      },
      updatePerfil(res, file) {
        this.perfil = res;
      },
    },
  };
</script>
