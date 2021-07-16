## ANTECEDENTES PROCURADURIA

_Paquete composer para Laravel que busca los antecedentes penales de una persona ante la Procuraduria_

## Comenzando 🚀

_Estas instrucciones te permitirán obtener una copia del proyecto en funcionamiento._


### Instalación 🔧

_para instalar el paquete ejecute el siguiente comando en consola:_

```
composer require 1026jota/antecedentes-procuraduria
npm install @nesk/puphpeteer
```
_después para publicar el archivo de configuración ejecuta siguiente comando:_

```
php artisan vendor:publish --provider='Jota\AntecedentesProcuraduria\Providers\AntecedentesProcuraduriaProviders'
```

## USO

```
    use Jota\SdnList\Classes\AntecedentesProcuraduria;

    $antecedentes = new AntecedentesProcuraduria();
    $antecedentes->searchByCedula('12345678');
    return $antecedentes->getResult();

```
## Ejemplo resultado

```
cuando la cédula no arroja resultado
[
  "is_registered" => false
  "result" => [
    "name" => ""
    "response" => "El número de cedula no genera resultados"
  ]
]

cuando la cédula no arroja resultado
[
  "is_registered" => true
  "result" => [
    "name" => "xxx xxx xxx xxx"
    "response" => "El ciudadano no presenta antecedentes"
  ]
]

```

## Autores ✒️

* **Jofree Alexander Montaño Nieto** - *developer* - [1026jota](https://github.com/1026jota)

## Licencia 📄

Este proyecto está bajo la Licencia (MIT).

---