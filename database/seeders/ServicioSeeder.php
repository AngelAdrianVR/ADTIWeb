<?php

namespace Database\Seeders;

use App\Models\Servicio;
use Illuminate\Database\Seeder;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = [
            [
                'key' => 'labeling',
                'title_es' => 'Sistemas de Etiquetado Industrial',
                'title_en' => 'Industrial Labeling Systems',
                'description_es' => 'Diseñamos e integramos sistemas de etiquetado y codificación de alta precisión para líneas de producción. Nuestras soluciones abarcan desde etiquetadoras automáticas, impresoras de inyección de tinta (CIJ), láser y transferencia térmica, hasta sistemas de visión para verificación de códigos y trazabilidad completa.',
                'description_en' => 'We design and integrate high-precision labeling and coding systems for production lines. Our solutions range from automatic labelers, continuous inkjet printers (CIJ), laser and thermal transfer printers, to vision systems for code verification and complete traceability.',
                'features_es' => json_encode([
                    'Etiquetado automático de alta velocidad',
                    'Codificación láser e inyección de tinta',
                    'Sistemas de visión para verificación',
                    'Trazabilidad y serialización',
                ]),
                'features_en' => json_encode([
                    'High-speed automatic labeling',
                    'Laser & inkjet coding',
                    'Vision inspection systems',
                    'Traceability & serialization',
                ]),
                'techs' => json_encode(['Markem-Imaje', 'Videojet', 'Domino', 'Cognex']),
                'sort_order' => 1,
            ],
            [
                'key' => 'conveyors',
                'title_es' => 'Bandas de Transportación',
                'title_en' => 'Conveyor Belt Systems',
                'description_es' => 'Fabricamos y automatizamos sistemas de transportación industrial a la medida: bandas modulares, de rodillos, sanitarias y sistemas de acumulación. Integramos sensores, variadores de velocidad y controles inteligentes para optimizar el flujo de materiales en tu planta.',
                'description_en' => 'We manufacture and automate custom industrial conveyor systems: modular belts, roller conveyors, sanitary conveyors, and accumulation systems. We integrate sensors, variable frequency drives, and intelligent controls to optimize material flow in your plant.',
                'features_es' => json_encode([
                    'Bandas modulares y de rodillos',
                    'Transportadores sanitarios (FDA)',
                    'Sistemas de acumulación y desvío',
                    'Control de velocidad variable',
                ]),
                'features_en' => json_encode([
                    'Modular & roller conveyors',
                    'Sanitary conveyors (FDA compliant)',
                    'Accumulation & diverter systems',
                    'Variable speed control',
                ]),
                'techs' => json_encode(['Intralox', 'Habasit', 'Dorner', 'FlexLink']),
                'sort_order' => 2,
            ],
            [
                'key' => 'eol',
                'title_es' => 'Automatización Fin de Línea',
                'title_en' => 'End-of-Line Automation',
                'description_es' => 'Optimizamos el final de tu línea de producción con soluciones integrales: encajonadoras, paletizadores robóticos, envolvedoras de stretch y sistemas de inspección final. Automatizamos el empaque secundario para maximizar tu productividad y reducir costos operativos.',
                'description_en' => 'We optimize the end of your production line with comprehensive solutions: case packers, robotic palletizers, stretch wrappers, and final inspection systems. We automate secondary packaging to maximize your productivity and reduce operational costs.',
                'features_es' => json_encode([
                    'Encajonadoras y formadoras de cajas',
                    'Paletizado robótico',
                    'Envolvedoras de stretch y flejado',
                    'Inspección y control de calidad final',
                ]),
                'features_en' => json_encode([
                    'Case erectors & packers',
                    'Robotic palletizing',
                    'Stretch wrapping & strapping',
                    'Final quality inspection',
                ]),
                'techs' => json_encode(['ABB Palletizing', 'FANUC', 'KUKA', 'Robopac']),
                'sort_order' => 3,
            ],
            [
                'key' => 'robotics',
                'title_es' => 'Celdas Robóticas',
                'title_en' => 'Robotic Cells',
                'description_es' => 'Diseñamos celdas robóticas llave en mano para aplicaciones de pick & place, soldadura, ensamblaje, pintura y manipulación de materiales. Integramos robots de 6 ejes, colaborativos (cobots) y SCARA con sistemas de seguridad perimetral y validación de procesos.',
                'description_en' => 'We design turnkey robotic cells for pick & place, welding, assembly, painting, and material handling applications. We integrate 6-axis robots, collaborative robots (cobots), and SCARA systems with perimeter safety and process validation.',
                'features_es' => json_encode([
                    'Robots de 6 ejes y colaborativos',
                    'Pick & place y paletizado',
                    'Soldadura y ensamblaje robotizado',
                    'Celdas con seguridad funcional',
                ]),
                'features_en' => json_encode([
                    '6-axis & collaborative robots',
                    'Pick & place & palletizing',
                    'Robotic welding & assembly',
                    'Functional safety cells',
                ]),
                'techs' => json_encode(['FANUC', 'Universal Robots', 'Yaskawa', 'ABB Robotics']),
                'sort_order' => 4,
            ],
            [
                'key' => 'machining',
                'title_es' => 'Manufactura Mecánica de Precisión',
                'title_en' => 'Precision Mechanical Manufacturing',
                'description_es' => 'Ofrecemos servicios de maquinado CNC, fresado, torneado y fabricación de piezas mecánicas bajo planos. Trabajamos con tolerancias estrictas y materiales diversos: aceros, aluminios, plásticos de ingeniería y aleaciones especiales para la industria automatizada.',
                'description_en' => 'We offer CNC machining, milling, turning, and mechanical parts fabrication from engineering drawings. We work with strict tolerances and diverse materials: steels, aluminum, engineering plastics, and special alloys for the automated industry.',
                'features_es' => json_encode([
                    'Fresado y torneado CNC',
                    'Fabricación bajo plano (DFM)',
                    'Mecanizado de precisión multicavidad',
                    'Acabados superficiales industriales',
                ]),
                'features_en' => json_encode([
                    'CNC milling & turning',
                    'DFM-based fabrication',
                    'Multi-cavity precision machining',
                    'Industrial surface finishing',
                ]),
                'techs' => json_encode(['Haas CNC', 'Mazak', 'DMG MORI', 'Mastercam']),
                'sort_order' => 5,
            ],
            [
                'key' => 'controls',
                'title_es' => 'Automatización de Controles',
                'title_en' => 'Control Automation',
                'description_es' => 'Programamos y configuramos sistemas de control industrial con PLCs, HMIs, variadores y redes de comunicación industrial. Desarrollamos lógicas de control avanzadas, recetas de producción y sistemas de adquisición de datos para la toma de decisiones en tiempo real.',
                'description_en' => 'We program and configure industrial control systems with PLCs, HMIs, drives, and industrial communication networks. We develop advanced control logic, production recipes, and data acquisition systems for real-time decision making.',
                'features_es' => json_encode([
                    'Programación de PLCs multi-marca',
                    'Redes Profinet, EtherNet/IP, Modbus',
                    'SCADA e historiadores de datos',
                    'Puesta en marcha y validación',
                ]),
                'features_en' => json_encode([
                    'Multi-brand PLC programming',
                    'Profinet, EtherNet/IP, Modbus networks',
                    'SCADA & data historians',
                    'Commissioning & validation',
                ]),
                'techs' => json_encode(['Siemens TIA Portal', 'Allen-Bradley', 'Omron Sysmac', 'Ignition SCADA']),
                'sort_order' => 6,
            ],
            [
                'key' => 'panels',
                'title_es' => 'Tableros de Control',
                'title_en' => 'Control Panels',
                'description_es' => 'Diseñamos y fabricamos tableros de control eléctrico conforme a las normativas UL 508A, IEC 61439 y NEMA. Desde el diagrama unifilar hasta el armado final, garantizamos calidad, seguridad y eficiencia energética en cada panel que entregamos.',
                'description_en' => 'We design and manufacture electrical control panels compliant with UL 508A, IEC 61439, and NEMA standards. From single-line diagrams to final assembly, we guarantee quality, safety, and energy efficiency in every panel we deliver.',
                'features_es' => json_encode([
                    'Diseño conforme a UL 508A / IEC',
                    'Diagramas unifilares y de control',
                    'Cálculo térmico y selectividad',
                    'Pruebas FAT y puesta en marcha',
                ]),
                'features_en' => json_encode([
                    'UL 508A / IEC compliant design',
                    'Single-line & control diagrams',
                    'Thermal & selectivity calculations',
                    'FAT testing & commissioning',
                ]),
                'techs' => json_encode(['UL 508A', 'IEC 61439', 'NEMA', 'EPLAN']),
                'sort_order' => 7,
            ],
        ];

        foreach ($servicios as $servicio) {
            Servicio::create($servicio);
        }
    }
}
