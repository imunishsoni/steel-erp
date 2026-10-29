/**
 * Steel ERP - Interactive Client-Side Application for GitHub Pages Showcase
 * Simulates Indian Heavy Steel Fabrication Management System (Power, Sugar & Cement Plants)
 * Data persisted in localStorage with live metrics, charts, modals, and print views.
 */

(function () {
    'use strict';

    // Default Seed Data based on Indian Heavy Steel Fabrication Database
    const defaultData = {
        company: {
            name: "Heavy Steel Fabricators Pvt. Ltd.",
            tagline: "Quality Heavy Steel Fabrication for Power, Sugar & Cement Plants",
            address: "Plot No. 45, Industrial Area Phase-II, Jaipur, Rajasthan - 302022",
            phone: "+91 141 2345678 / +91 98290 12345",
            email: "contact@heavysteelfab.in",
            gstin: "08AABCU9603R1ZM",
            pan: "AABCU9603R",
            bankName: "HDFC Bank Ltd, Industrial Estate Branch, Jaipur",
            bankAcc: "50200088991122",
            ifsc: "HDFC0000054",
            stateCode: "08-Rajasthan"
        },
        clients: [
            {
                id: 1,
                code: "CLI-POW-001",
                name: "NTPC Limited (Ramagundam STPP)",
                sector: "power_plant",
                sectorLabel: "Power Plant",
                contact: "Er. K. Venkat Rao (Chief Engg)",
                phone: "9440112233",
                email: "procurement.ramagundam@ntpc.co.in",
                city: "Ramagundam",
                state: "Telangana",
                gstin: "36AAACN0255D1ZQ",
                creditLimit: 25000000,
                activeJobs: 2,
                notes: "PSU Thermal Power Client - High Grade SA516 Boiler Structures"
            },
            {
                id: 2,
                code: "CLI-POW-002",
                name: "Adani Power (Mundra) Limited",
                sector: "power_plant",
                sectorLabel: "Power Plant",
                contact: "Suresh Patel (GM - Projects)",
                phone: "9898011223",
                email: "spatel@adanipower.com",
                city: "Mundra, Kutch",
                state: "Gujarat",
                gstin: "24AABCA2984K1ZH",
                creditLimit: 18000000,
                activeJobs: 1,
                notes: "Ultra Supercritical Thermal Power Station ESP Casings and Girders"
            },
            {
                id: 3,
                code: "CLI-SUG-001",
                name: "Bajaj Hindusthan Sugar Limited",
                sector: "sugar_mill",
                sectorLabel: "Sugar Mill",
                contact: "Maheshwar Mishra (Mill Engr)",
                phone: "9415088776",
                email: "purchases.gola@bajajhindusthan.com",
                city: "Lakhimpur Kheri",
                state: "Uttar Pradesh",
                gstin: "09AAACB3134J1ZA",
                creditLimit: 12000000,
                activeJobs: 1,
                notes: "Sugar Mill Evaporator Bodies, Vacuum Pans and Cane Carrier Structures"
            },
            {
                id: 4,
                code: "CLI-SUG-002",
                name: "Shree Renuka Sugars Limited",
                sector: "sugar_mill",
                sectorLabel: "Sugar Mill",
                contact: "Vinayak Kulkarni (VP Tech)",
                phone: "9845033445",
                email: "vkulkarni@renukasugars.com",
                city: "Belagavi",
                state: "Karnataka",
                gstin: "29AABCS8891P1ZX",
                creditLimit: 15000000,
                activeJobs: 0,
                notes: "Refinery Crystallizers and High-Pressure Steam Piping Supports"
            },
            {
                id: 5,
                code: "CLI-CEM-001",
                name: "UltraTech Cement Ltd (Birla White)",
                sector: "cement_plant",
                sectorLabel: "Cement Plant",
                contact: "Anil Jodhpurkar (AGM Engg)",
                phone: "9414022334",
                email: "procurement.bw@adityabirla.com",
                city: "Jodhpur",
                state: "Rajasthan",
                gstin: "08AAACU4998K1ZT",
                creditLimit: 20000000,
                activeJobs: 1,
                notes: "Kiln Preheater Cyclones, Baghouse Structures & Clinker Coolers (Intra-state supply)"
            },
            {
                id: 6,
                code: "CLI-CEM-002",
                name: "Shree Cement Limited",
                sector: "cement_plant",
                sectorLabel: "Cement Plant",
                contact: "Dharmendra Joshi (DGM Mech)",
                phone: "9829088990",
                email: "djoshi@shreecement.com",
                city: "Beawar",
                state: "Rajasthan",
                gstin: "08AAACS1363Q1ZU",
                creditLimit: 22000000,
                activeJobs: 0,
                notes: "Raw Mill Ducting, Silo Structural Supports and Conveyor Galleries"
            }
        ],
        productionJobs: [
            {
                id: 1,
                jobNo: "JOB-2627-001",
                client: "NTPC Limited (Ramagundam STPP)",
                sector: "Power Plant",
                title: "800MW Stage-IV Boiler Structure & ESP Ducting",
                tonnage: 87.0,
                completedTonnage: 56.5,
                drawingNo: "NTPC-RAM-BLR-D-4019",
                startDate: "2026-10-10",
                targetDate: "2026-12-02",
                priority: "High",
                status: "In Progress",
                currentStage: "Blasting & Painting",
                stageProgress: 65,
                assignedBay: "Heavy Bay 1",
                stages: [
                    { name: "Raw Material Issue & Traceability Check", status: "completed", date: "2026-10-12" },
                    { name: "CNC Oxy-Fuel Plate Cutting & Edge Prep", status: "completed", date: "2026-10-16" },
                    { name: "Sub-assembly Fit-up & Tack Welding", status: "completed", date: "2026-10-21" },
                    { name: "SAW & GMAW Full Penetration Welding", status: "completed", date: "2026-10-24" },
                    { name: "NDT 100% Ultrasonic & Radiography Testing", status: "completed", date: "2026-10-26" },
                    { name: "Surface Grit Blasting to SA 2.5", status: "in_progress", date: "2026-10-28" },
                    { name: "High Build Epoxy & PU Finish Painting", status: "pending", date: "Expected 2026-11-02" },
                    { name: "Final Client TPI Inspection & Clearance", status: "pending", date: "Expected 2026-11-05" }
                ],
                notes: "First consignment (20 MT) already dispatched via DC-2627-001. Second lot undergoing blasting in Bay 1."
            },
            {
                id: 2,
                jobNo: "JOB-2627-002",
                client: "UltraTech Cement Ltd (Birla White)",
                sector: "Cement Plant",
                title: "White Cement Kiln-2 Cyclone Preheater Ducting",
                tonnage: 43.0,
                completedTonnage: 21.5,
                drawingNo: "UTC-K2-CYC-9921",
                startDate: "2026-10-16",
                targetDate: "2026-11-17",
                priority: "Urgent",
                status: "In Progress",
                currentStage: "SAW Welding & DP Testing",
                stageProgress: 50,
                assignedBay: "Bay 2",
                stages: [
                    { name: "Raw Material Issue & Traceability Check", status: "completed", date: "2026-10-17" },
                    { name: "Plate Rolling & Conical Cone Forming", status: "completed", date: "2026-10-20" },
                    { name: "Sub-assembly Fit-up & Tack Welding", status: "completed", date: "2026-10-23" },
                    { name: "SAW & GMAW Full Penetration Welding", status: "in_progress", date: "2026-10-27" },
                    { name: "NDT 100% Ultrasonic & Radiography Testing", status: "in_progress", date: "2026-10-29" },
                    { name: "Surface Grit Blasting to SA 2.5", status: "pending", date: "Expected 2026-11-03" },
                    { name: "High Build Epoxy & PU Finish Painting", status: "pending", date: "Expected 2026-11-06" },
                    { name: "Final Client TPI Inspection & Clearance", status: "pending", date: "Expected 2026-11-09" }
                ],
                notes: "Batch 1 (14 MT) dispatched via DC-2627-002. Phase 2 cone shell welding underway."
            },
            {
                id: 3,
                jobNo: "JOB-2627-003",
                client: "Bajaj Hindusthan Sugar Limited",
                sector: "Sugar Mill",
                title: "Standard 12m Heavy Roof Trusses & Conveyor Modules",
                tonnage: 15.0,
                completedTonnage: 3.0,
                drawingNo: "BHS-TRUSS-12M-04",
                startDate: "2026-10-24",
                targetDate: "2026-11-22",
                priority: "Medium",
                status: "Material Issued",
                currentStage: "Fit-Up & Gusset Tack",
                stageProgress: 20,
                assignedBay: "Structural Bay 3",
                stages: [
                    { name: "Raw Material Issue & Traceability Check", status: "completed", date: "2026-10-25" },
                    { name: "CNC Oxy-Fuel Plate Cutting & Edge Prep", status: "completed", date: "2026-10-27" },
                    { name: "Sub-assembly Fit-up & Tack Welding", status: "in_progress", date: "2026-10-28" },
                    { name: "SAW & GMAW Full Penetration Welding", status: "pending", date: "Expected 2026-11-04" },
                    { name: "NDT 100% Ultrasonic & Radiography Testing", status: "pending", date: "Expected 2026-11-08" },
                    { name: "Surface Grit Blasting to SA 2.5", status: "pending", date: "Expected 2026-11-12" },
                    { name: "High Build Epoxy & PU Finish Painting", status: "pending", date: "Expected 2026-11-15" },
                    { name: "Final Client TPI Inspection & Clearance", status: "pending", date: "Expected 2026-11-18" }
                ],
                notes: "Chord angles cut to size. Gusset plates drilling in progress on radial arm drill."
            },
            {
                id: 4,
                jobNo: "JOB-2627-004",
                client: "Adani Power (Mundra) Limited",
                sector: "Power Plant",
                title: "FGD Absorption Tower Structural Columns & Bracings",
                tonnage: 62.0,
                completedTonnage: 18.6,
                drawingNo: "APML-FGD-COL-7703",
                startDate: "2026-10-20",
                targetDate: "2026-12-15",
                priority: "High",
                status: "In Progress",
                currentStage: "Sub-assembly Fit-up",
                stageProgress: 30,
                assignedBay: "Heavy Bay 1",
                stages: [
                    { name: "Raw Material Issue & Traceability Check", status: "completed", date: "2026-10-21" },
                    { name: "CNC Oxy-Fuel Plate Cutting & Edge Prep", status: "completed", date: "2026-10-24" },
                    { name: "Sub-assembly Fit-up & Tack Welding", status: "in_progress", date: "2026-10-28" },
                    { name: "SAW & GMAW Full Penetration Welding", status: "pending", date: "Expected 2026-11-06" },
                    { name: "NDT 100% Ultrasonic & Radiography Testing", status: "pending", date: "Expected 2026-11-10" },
                    { name: "Surface Grit Blasting to SA 2.5", status: "pending", date: "Expected 2026-11-15" },
                    { name: "High Build Epoxy & PU Finish Painting", status: "pending", date: "Expected 2026-11-20" },
                    { name: "Final Client TPI Inspection & Clearance", status: "pending", date: "Expected 2026-11-25" }
                ],
                notes: "Built-up heavy H-beams web and flange plates positioned on bed for automatic SAW."
            }
        ],
        inventory: [
            {
                id: 1,
                itemCode: "PL-IS2062-25",
                name: "IS 2062 Gr E250 Mild Steel Plate (25mm x 2500 x 10000)",
                category: "Steel Plates",
                mill: "SAIL (Bhilai Steel Plant)",
                heatNo: "BH-4892-A",
                mtcNo: "MTC/SAIL/2026/9912",
                bay: "Yard Bay A",
                stockMT: 142.5,
                unit: "MT",
                minStockMT: 30.0,
                ratePerMT: 62000,
                status: "Available"
            },
            {
                id: 2,
                itemCode: "PL-SA516-32",
                name: "SA 516 Gr 70 Boiler Quality Plate (32mm x 2000 x 8000)",
                category: "Boiler Quality Plates",
                mill: "Jindal Steel & Power (JSPL Angul)",
                heatNo: "JS-9021-BQ",
                mtcNo: "MTC/JSPL/2026/4102",
                bay: "Yard Bay B",
                stockMT: 88.0,
                unit: "MT",
                minStockMT: 25.0,
                ratePerMT: 74000,
                status: "Available"
            },
            {
                id: 3,
                itemCode: "SEC-ISMB-400",
                name: "ISMB 400 Heavy Rolled Beams (12 Meter Lengths)",
                category: "Structural Rolled Sections",
                mill: "Tata Steel (Jamshedpur Works)",
                heatNo: "TS-1142-HB",
                mtcNo: "MTC/TATA/2026/8841",
                bay: "Yard Bay C",
                stockMT: 64.0,
                unit: "MT",
                minStockMT: 20.0,
                ratePerMT: 65500,
                status: "Available"
            },
            {
                id: 4,
                itemCode: "SEC-ISMC-200",
                name: "ISMC 200 Heavy Channels (IS 2062 E250)",
                category: "Structural Rolled Sections",
                mill: "SAIL (Durgapur Steel Plant)",
                heatNo: "SD-3381-C",
                mtcNo: "MTC/SAIL/2026/7723",
                bay: "Yard Bay C",
                stockMT: 45.2,
                unit: "MT",
                minStockMT: 15.0,
                ratePerMT: 63000,
                status: "Available"
            },
            {
                id: 5,
                itemCode: "CONS-E7018",
                name: "E7018 Low Hydrogen Welding Electrodes (4.0mm)",
                category: "Welding Consumables",
                mill: "ESAB India Limited",
                heatNo: "Batch ES-26-88",
                mtcNo: "TC/ESAB/2026/1029",
                bay: "Store Rack S-1",
                stockMT: 4.8,
                unit: "MT",
                minStockMT: 1.5,
                ratePerMT: 145000,
                status: "Available"
            },
            {
                id: 6,
                itemCode: "PL-SA387-11",
                name: "SA 387 Gr 11 Alloy Steel Clad Plate (16mm)",
                category: "Alloy Steel Plates",
                mill: "JSPL (Raigarh Works)",
                heatNo: "JR-6612-AL",
                mtcNo: "MTC/JSPL/2026/5091",
                bay: "Yard Bay B",
                stockMT: 28.5,
                unit: "MT",
                minStockMT: 10.0,
                ratePerMT: 96000,
                status: "Available"
            }
        ],
        dispatches: [
            {
                id: 1,
                dcNo: "DC-2627-001",
                date: "2026-10-22",
                jobNo: "JOB-2627-001",
                client: "NTPC Limited (Ramagundam STPP)",
                destination: "NTPC Super Thermal Power Project, Ramagundam, Telangana - 505215",
                transporter: "Jaipur Golden Transport Co.",
                vehicleNo: "RJ-14-GC-8842 (40ft High Bed Trailer)",
                driverName: "Balram Gurjar",
                driverPhone: "9829988112",
                lrNo: "JG-JPR-2026-904",
                ewayBill: "241890334812",
                grossWt: 38.65,
                tareWt: 18.65,
                netWt: 20.00,
                packages: 8,
                status: "In Transit",
                remarks: "Consignment safely lashed with steel chocks and nylon web straps. TPI stamp verified."
            },
            {
                id: 2,
                dcNo: "DC-2627-002",
                date: "2026-10-25",
                jobNo: "JOB-2627-002",
                client: "UltraTech Cement Ltd (Birla White)",
                destination: "Kharia Khangar Works, Jodhpur, Rajasthan - 342606",
                transporter: "Shree Marwar Freight Carrier",
                vehicleNo: "RJ-19-GA-3321 (Multi-Axle Truck)",
                driverName: "Kishan Lal Bishnoi",
                driverPhone: "9414433221",
                lrNo: "SM-JPR-4412",
                ewayBill: "281890455913",
                grossWt: 27.80,
                tareWt: 13.80,
                netWt: 14.00,
                packages: 2,
                status: "Delivered",
                remarks: "Delivered at UltraTech central store. Material receipt endorsed on LR copy."
            },
            {
                id: 3,
                dcNo: "DC-2627-003",
                date: "2026-10-27",
                jobNo: "JOB-2627-004",
                client: "Adani Power (Mundra) Limited",
                destination: "Adani House, Village Tunda & Wandh, Mundra, Kutch, Gujarat - 370435",
                transporter: "Western Bulk Carriers",
                vehicleNo: "GJ-12-AY-9014 (Semi-Trailer)",
                driverName: "Mansukhbhai Patel",
                driverPhone: "9825129988",
                lrNo: "WBC-2026-781",
                ewayBill: "241890667104",
                grossWt: 34.20,
                tareWt: 16.20,
                netWt: 18.00,
                packages: 6,
                status: "Dispatched",
                remarks: "Heavy structural members primed with zinc-silicate coat. Valid for 4 days."
            }
        ],
        invoices: [
            {
                id: 1,
                invNo: "INV-2627-001",
                date: "2026-10-22",
                client: "NTPC Limited (Ramagundam STPP)",
                clientGstin: "36AAACN0255D1ZQ",
                state: "36-Telangana",
                supplyType: "Inter-State (IGST 18%)",
                hsn: "730890",
                description: "Fabricated Heavy ESP Casing Panels & Internal Baffles (Lot 1)",
                weightMT: 20.0,
                ratePerMT: 88000,
                taxableAmount: 1760000,
                cgst: 0,
                sgst: 0,
                igst: 316800,
                totalAmount: 2076800,
                dcRef: "DC-2627-001",
                status: "Partially Paid",
                paidAmount: 1147000,
                dueAmount: 929800,
                irn: "e87f2b1a9c3d4e5f60718293a4b5c6d7e8f9a0b1c2d3e4f5a6b7c8d9e0f1a2b3"
            },
            {
                id: 2,
                invNo: "INV-2627-002",
                date: "2026-10-25",
                client: "UltraTech Cement Ltd (Birla White)",
                clientGstin: "08AAACU4998K1ZT",
                state: "08-Rajasthan",
                supplyType: "Intra-State (CGST 9% + SGST 9%)",
                hsn: "730890",
                description: "Rolled Preheater Conical Ductwork & Discharge Chutes (Phase 1)",
                weightMT: 14.0,
                ratePerMT: 90000,
                taxableAmount: 1260000,
                cgst: 113400,
                sgst: 113400,
                igst: 0,
                totalAmount: 1486800,
                dcRef: "DC-2627-002",
                status: "Sent",
                paidAmount: 0,
                dueAmount: 1486800,
                irn: "f19a2b3c4d5e6f7a8b9c0d1e2f3a4b5c6d7e8f9a0b1c2d3e4f5a6b7c8d9e0f1"
            },
            {
                id: 3,
                invNo: "INV-2627-003",
                date: "2026-10-27",
                client: "Adani Power (Mundra) Limited",
                clientGstin: "24AABCA2984K1ZH",
                state: "24-Gujarat",
                supplyType: "Inter-State (IGST 18%)",
                hsn: "730890",
                description: "Fabricated FGD Column Assemblies & Heavy Tie Bracings",
                weightMT: 18.0,
                ratePerMT: 87777.78,
                taxableAmount: 1580000,
                cgst: 0,
                sgst: 0,
                igst: 284400,
                totalAmount: 1864400,
                dcRef: "DC-2627-003",
                status: "Sent",
                paidAmount: 0,
                dueAmount: 1864400,
                irn: "9c3d4e5f60718293a4b5c6d7e8f9a0b1c2d3e4f5a6b7c8d9e0f1a2b3e87f2b1a"
            }
        ],
        quotations: [
            {
                id: 1,
                qtnNo: "QTN-2627-001",
                date: "2026-10-04",
                client: "NTPC Limited (Ramagundam STPP)",
                sector: "Power Plant",
                title: "800MW Stage-IV Boiler Structure & ESP Ducting (87 MT)",
                tonnage: 87.0,
                amount: 7850000,
                validUntil: "2026-11-04",
                status: "Won",
                notes: "Tender won against BHEL specification. PO released."
            },
            {
                id: 2,
                qtnNo: "QTN-2627-002",
                date: "2026-10-08",
                client: "UltraTech Cement Ltd (Birla White)",
                sector: "Cement Plant",
                title: "Kiln Cyclone Ducting & Discharge Chutes (43 MT)",
                tonnage: 43.0,
                amount: 3870000,
                validUntil: "2026-11-08",
                status: "Approved",
                notes: "Approved by Technical Committee. Advance received."
            },
            {
                id: 3,
                qtnNo: "QTN-2627-003",
                date: "2026-10-14",
                client: "Shree Renuka Sugars Limited",
                sector: "Sugar Mill",
                title: "Crystallizer Support Platforms, Walkways & Cane Carrier (35 MT)",
                tonnage: 35.0,
                amount: 2940000,
                validUntil: "2026-11-14",
                status: "Sent",
                notes: "Commercial negotiation completed; waiting for final board clearance."
            },
            {
                id: 4,
                qtnNo: "QTN-2627-004",
                date: "2026-10-21",
                client: "Shree Cement Limited",
                sector: "Cement Plant",
                title: "Pre-calciner Clinker Ductwork & Heavy Silo Chutes (50 MT)",
                tonnage: 50.0,
                amount: 4420000,
                validUntil: "2026-11-21",
                status: "Draft",
                notes: "Drawing review in progress with project engineering team."
            }
        ],
        qaqc: [
            {
                id: 1,
                reportNo: "UT-2627-101",
                date: "2026-10-26",
                jobNo: "JOB-2627-001",
                jointId: "WJ-ESP-01 to 08",
                process: "Submerged Arc Welding (SAW)",
                plateThickness: "32mm SA 516 Gr 70",
                method: "Ultrasonic Testing (UT)",
                acceptanceStd: "AWS D1.1 / ASME Sec VIII",
                result: "ACCEPTED",
                inspector: "R. Sharma (ASNT Level II)",
                tpiAgency: "TUV India Pvt. Ltd."
            },
            {
                id: 2,
                reportNo: "RT-2627-042",
                date: "2026-10-26",
                jobNo: "JOB-2627-001",
                jointId: "FL-CAS-14",
                process: "Full Penetration Butt Weld",
                plateThickness: "25mm IS 2062",
                method: "Radiographic Testing (RT - Ir-192)",
                acceptanceStd: "ASME Sec VIII Div 1",
                result: "ACCEPTED",
                inspector: "S. K. Verma (BARC Level II)",
                tpiAgency: "SGS India Ltd."
            },
            {
                id: 3,
                reportNo: "DPT-2627-088",
                date: "2026-10-27",
                jobNo: "JOB-2627-002",
                jointId: "CYC-CONE-FILLET-01",
                process: "GMAW / MIG Fillet Welds",
                plateThickness: "16mm IS 2062",
                method: "Liquid Dye Penetrant (DPT)",
                acceptanceStd: "IS 3658 / ASME Sec V",
                result: "ACCEPTED",
                inspector: "P. Nair (NDT Level II)",
                tpiAgency: "In-house QA"
            },
            {
                id: 4,
                reportNo: "MPT-2627-033",
                date: "2026-10-27",
                jobNo: "JOB-2627-004",
                jointId: "LIFT-TRUNNION-02",
                process: "Lifting Lug Full Weld",
                plateThickness: "40mm IS 2062",
                method: "Magnetic Particle (MPT - AC Yoke)",
                acceptanceStd: "AWS D1.1 Clause 6",
                result: "ACCEPTED",
                inspector: "R. Sharma (ASNT Level II)",
                tpiAgency: "Bureau Veritas"
            }
        ]
    };

    // LocalStorage Key
    const STORAGE_KEY = 'steel_erp_demo_db_v1';

    // State object
    let appData = {};

    function initData() {
        const stored = localStorage.getItem(STORAGE_KEY);
        if (stored) {
            try {
                appData = JSON.parse(stored);
            } catch (e) {
                console.error("Failed to parse stored Steel ERP data. Reverting to defaults.", e);
                appData = JSON.parse(JSON.stringify(defaultData));
                saveData();
            }
        } else {
            appData = JSON.parse(JSON.stringify(defaultData));
            saveData();
        }
    }

    function saveData() {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(appData));
    }

    function resetData() {
        if (confirm("Reset all demo data back to factory Indian Steel Fabrication sample data? Any added Job Orders or records will be refreshed.")) {
            localStorage.removeItem(STORAGE_KEY);
            initData();
            renderAll();
            showToast("Demo data successfully reset to defaults!", "success");
        }
    }

    // Number formatters
    function formatINR(val) {
        val = parseFloat(val) || 0;
        return "₹" + val.toLocaleString('en-IN', { maximumFractionDigits: 2 });
    }

    function formatMT(val) {
        val = parseFloat(val) || 0;
        return val.toLocaleString('en-IN', { minimumFractionDigits: 1, maximumFractionDigits: 3 }) + " MT";
    }

    // Toast notification
    function showToast(message, type) {
        type = type || 'primary';
        const toastId = 'toast-' + Date.now();
        const html = `
            <div id="${toastId}" class="toast align-items-center text-white bg-${type === 'error' ? 'danger' : type} border-0 show shadow-lg mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-info-circle-fill me-2"></i> ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        $('#toastContainer').append(html);
        setTimeout(() => {
            $(`#${toastId}`).fadeOut(300, function () { $(this).remove(); });
        }, 4000);
    }

    // Render Dashboard
    let outputChartInstance = null;
    let sectorChartInstance = null;

    function renderDashboard() {
        const totalJobs = appData.productionJobs.length;
        const activeJobs = appData.productionJobs.filter(j => j.status !== 'Completed').length;
        const totalStockMT = appData.inventory.reduce((sum, item) => sum + item.stockMT, 0);
        const totalInvoiced = appData.invoices.reduce((sum, inv) => sum + inv.totalAmount, 0);
        const dispatchedMT = appData.dispatches.reduce((sum, d) => sum + d.netWt, 0);

        $('#kpiActiveJobs').text(activeJobs);
        $('#kpiStockMT').text(formatMT(totalStockMT));
        $('#kpiInvoiced').text(formatINR(totalInvoiced));
        $('#kpiDispatchedMT').text(formatMT(dispatchedMT));
        $('#kpiTotalClients').text(appData.clients.length);
        $('#kpiTotalQtn').text(appData.quotations.length);

        // Render Recent Jobs table on Dashboard
        let jobsHtml = '';
        appData.productionJobs.slice(0, 4).forEach(job => {
            const badgeClass = job.status === 'Completed' ? 'bg-success' : (job.priority === 'Urgent' ? 'bg-danger' : 'bg-primary');
            jobsHtml += `
                <tr>
                    <td><strong class="text-primary font-monospace">${job.jobNo}</strong></td>
                    <td>
                        <div class="fw-semibold">${job.client}</div>
                        <small class="text-muted">${job.title}</small>
                    </td>
                    <td><span class="badge bg-secondary">${job.sector}</span></td>
                    <td><strong>${job.tonnage} MT</strong></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: ${job.stageProgress}%"></div>
                            </div>
                            <span class="small text-muted">${job.stageProgress}%</span>
                        </div>
                        <small class="text-primary">${job.currentStage}</small>
                    </td>
                    <td><span class="badge ${badgeClass}">${job.status}</span></td>
                </tr>
            `;
        });
        $('#dashboardJobsTable tbody').html(jobsHtml);

        // Charts
        renderDashboardCharts();
    }

    function renderDashboardCharts() {
        if (typeof Chart === 'undefined') return;

        // Chart 1: Output Fabrication vs Dispatch
        const ctxOutput = document.getElementById('fabricationOutputChart');
        if (ctxOutput) {
            if (outputChartInstance) outputChartInstance.destroy();
            outputChartInstance = new Chart(ctxOutput, {
                type: 'bar',
                data: {
                    labels: ['May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct 2026'],
                    datasets: [
                        {
                            label: 'Fabricated (MT)',
                            data: [320, 390, 440, 410, 480, 520],
                            backgroundColor: '#1e3a5f',
                            borderRadius: 4
                        },
                        {
                            label: 'Dispatched (MT)',
                            data: [290, 370, 420, 395, 450, 490],
                            backgroundColor: '#f97316',
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Metric Tonnes (MT)' }
                        }
                    }
                }
            });
        }

        // Chart 2: Client Sector Distribution
        const ctxSector = document.getElementById('sectorDistributionChart');
        if (ctxSector) {
            if (sectorChartInstance) sectorChartInstance.destroy();

            // Count tonnage by sector
            let powerMT = 0, sugarMT = 0, cementMT = 0;
            appData.productionJobs.forEach(j => {
                const s = (j.sector || '').toLowerCase();
                if (s.includes('power')) powerMT += j.tonnage;
                else if (s.includes('sugar')) sugarMT += j.tonnage;
                else if (s.includes('cement')) cementMT += j.tonnage;
                else powerMT += j.tonnage;
            });

            sectorChartInstance = new Chart(ctxSector, {
                type: 'doughnut',
                data: {
                    labels: ['Thermal Power Plants (54%)', 'Cement Plants (27%)', 'Sugar Mills (19%)'],
                    datasets: [{
                        data: [powerMT || 149, cementMT || 43, sugarMT || 15],
                        backgroundColor: ['#1e3a5f', '#f97316', '#10b981'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }
    }

    // Render Clients Table
    function renderClients(filterSector) {
        let list = appData.clients;
        if (filterSector && filterSector !== 'all') {
            list = list.filter(c => c.sector === filterSector);
        }

        let html = '';
        list.forEach(c => {
            const sectorBadge = c.sector === 'power_plant' ? 'bg-primary' : (c.sector === 'sugar_mill' ? 'bg-success' : 'bg-warning text-dark');
            html += `
                <tr>
                    <td><strong class="font-monospace text-primary">${c.code}</strong></td>
                    <td>
                        <div class="fw-bold">${c.name}</div>
                        <small class="text-muted"><i class="bi bi-geo-alt"></i> ${c.city}, ${c.state}</small>
                    </td>
                    <td><span class="badge ${sectorBadge}">${c.sectorLabel}</span></td>
                    <td>
                        <div>${c.contact}</div>
                        <small class="text-muted"><i class="bi bi-telephone"></i> ${c.phone}</small>
                    </td>
                    <td><span class="font-monospace small bg-light p-1 border rounded">${c.gstin}</span></td>
                    <td><strong>${formatINR(c.creditLimit)}</strong></td>
                    <td class="text-center">
                        <span class="badge bg-info text-dark">${c.activeJobs} Jobs</span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary view-client-btn" data-id="${c.id}" title="View Details">
                            <i class="bi bi-eye"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        $('#clientsTable tbody').html(html);
    }

    // Render Production Floor / Job Orders
    function renderProductionJobs() {
        let html = '';
        appData.productionJobs.forEach(job => {
            const priorityBadge = job.priority === 'Urgent' ? 'bg-danger' : (job.priority === 'High' ? 'bg-warning text-dark' : 'bg-secondary');
            const statusBadge = job.status === 'Completed' ? 'bg-success' : 'bg-primary';

            html += `
                <tr>
                    <td><strong class="font-monospace text-primary">${job.jobNo}</strong></td>
                    <td>
                        <div class="fw-bold">${job.client}</div>
                        <div class="text-dark">${job.title}</div>
                        <small class="text-muted">Drawing: <code>${job.drawingNo}</code> | Assigned: <span class="badge bg-light text-dark border">${job.assignedBay}</span></small>
                    </td>
                    <td><span class="badge bg-secondary">${job.sector}</span></td>
                    <td>
                        <strong>${job.tonnage} MT</strong>
                        <div class="small text-muted">Completed: ${job.completedTonnage} MT</div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center mb-1">
                            <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" style="width: ${job.stageProgress}%"></div>
                            </div>
                            <span class="small fw-bold">${job.stageProgress}%</span>
                        </div>
                        <span class="badge bg-dark">${job.currentStage}</span>
                    </td>
                    <td><span class="badge ${priorityBadge}">${job.priority}</span></td>
                    <td><span class="badge ${statusBadge}">${job.status}</span></td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary view-job-btn" data-id="${job.id}" title="View Traveler & Stages">
                                <i class="bi bi-diagram-3"></i> Traveler
                            </button>
                            <button class="btn btn-outline-success advance-stage-btn" data-id="${job.id}" title="Advance to Next Stage">
                                <i class="bi bi-arrow-right-circle"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        $('#productionJobsTable tbody').html(html);
    }

    // Render Yard Inventory
    function renderInventory() {
        let html = '';
        appData.inventory.forEach(item => {
            const isLow = item.stockMT <= item.minStockMT;
            const stockBadge = isLow ? 'bg-danger' : 'bg-success';
            html += `
                <tr>
                    <td><strong class="font-monospace text-primary">${item.itemCode}</strong></td>
                    <td>
                        <div class="fw-bold">${item.name}</div>
                        <small class="text-muted">Mill: ${item.mill}</small>
                    </td>
                    <td><span class="badge bg-light text-dark border">${item.category}</span></td>
                    <td><span class="font-monospace fw-bold text-danger">${item.heatNo}</span></td>
                    <td><code class="text-primary">${item.mtcNo}</code></td>
                    <td><span class="badge bg-secondary">${item.bay}</span></td>
                    <td>
                        <strong class="fs-6">${formatMT(item.stockMT)}</strong>
                        <span class="badge ${stockBadge} ms-1">${isLow ? 'Low Stock' : 'Adequate'}</span>
                    </td>
                    <td>${formatINR(item.ratePerMT)}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary issue-mat-btn" data-id="${item.id}" title="Issue to Job Order">
                            <i class="bi bi-box-arrow-right"></i> Issue
                        </button>
                    </td>
                </tr>
            `;
        });
        $('#inventoryTable tbody').html(html);
    }

    // Render Dispatches Table
    function renderDispatches() {
        let html = '';
        appData.dispatches.forEach(d => {
            const statusBadge = d.status === 'Delivered' ? 'bg-success' : (d.status === 'In Transit' ? 'bg-warning text-dark' : 'bg-primary');
            html += `
                <tr>
                    <td><strong class="font-monospace text-primary">${d.dcNo}</strong></td>
                    <td>${d.date}</td>
                    <td>
                        <div class="fw-bold">${d.client}</div>
                        <small class="text-muted"><i class="bi bi-geo-alt"></i> ${d.destination}</small>
                    </td>
                    <td>
                        <div><strong class="font-monospace text-dark">${d.vehicleNo}</strong></div>
                        <small class="text-muted">${d.transporter}</small>
                    </td>
                    <td><span class="font-monospace small bg-light p-1 border rounded">${d.ewayBill}</span></td>
                    <td>
                        <strong class="text-success">${formatMT(d.netWt)}</strong>
                        <div class="small text-muted">Tare: ${d.tareWt} | Gross: ${d.grossWt}</div>
                    </td>
                    <td><span class="badge ${statusBadge}">${d.status}</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary print-dc-btn" data-id="${d.id}" title="Print Delivery Challan">
                            <i class="bi bi-printer"></i> Print DC
                        </button>
                    </td>
                </tr>
            `;
        });
        $('#dispatchesTable tbody').html(html);
    }

    // Render Invoices Table
    function renderInvoices() {
        let html = '';
        appData.invoices.forEach(inv => {
            const statusBadge = inv.status === 'Paid' ? 'bg-success' : (inv.status === 'Partially Paid' ? 'bg-warning text-dark' : 'bg-primary');
            html += `
                <tr>
                    <td><strong class="font-monospace text-primary">${inv.invNo}</strong></td>
                    <td>${inv.date}</td>
                    <td>
                        <div class="fw-bold">${inv.client}</div>
                        <small class="text-muted">GSTIN: ${inv.clientGstin}</small>
                    </td>
                    <td><span class="badge bg-secondary font-monospace">HSN ${inv.hsn}</span></td>
                    <td>${formatINR(inv.taxableAmount)}</td>
                    <td>
                        ${inv.igst > 0 ? `<div class="small">IGST (18%): ${formatINR(inv.igst)}</div>` : `<div class="small">CGST (9%): ${formatINR(inv.cgst)}<br>SGST (9%): ${formatINR(inv.sgst)}</div>`}
                    </td>
                    <td><strong class="text-primary fs-6">${formatINR(inv.totalAmount)}</strong></td>
                    <td><span class="badge ${statusBadge}">${inv.status}</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary print-inv-btn" data-id="${inv.id}" title="Print GST Tax Invoice">
                            <i class="bi bi-printer"></i> Print
                        </button>
                    </td>
                </tr>
            `;
        });
        $('#invoicesTable tbody').html(html);
    }

    // Render Quotations Table
    function renderQuotations() {
        let html = '';
        appData.quotations.forEach(q => {
            const statusBadge = q.status === 'Won' ? 'bg-success' : (q.status === 'Approved' ? 'bg-info text-dark' : (q.status === 'Sent' ? 'bg-primary' : 'bg-secondary'));
            html += `
                <tr>
                    <td><strong class="font-monospace text-primary">${q.qtnNo}</strong></td>
                    <td>${q.date}</td>
                    <td>
                        <div class="fw-bold">${q.client}</div>
                        <small class="text-muted">${q.title}</small>
                    </td>
                    <td><span class="badge bg-light text-dark border">${q.sector}</span></td>
                    <td><strong>${q.tonnage} MT</strong></td>
                    <td><strong class="text-primary">${formatINR(q.amount)}</strong></td>
                    <td><span class="badge ${statusBadge}">${q.status}</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary view-qtn-btn" data-id="${q.id}">
                            <i class="bi bi-eye"></i> View
                        </button>
                    </td>
                </tr>
            `;
        });
        $('#quotationsTable tbody').html(html);
    }

    // Render QA / QC Table
    function renderQAQC() {
        let html = '';
        appData.qaqc.forEach(item => {
            html += `
                <tr>
                    <td><strong class="font-monospace text-primary">${item.reportNo}</strong></td>
                    <td>${item.date}</td>
                    <td><span class="badge bg-secondary font-monospace">${item.jobNo}</span></td>
                    <td>
                        <div class="fw-bold">${item.jointId}</div>
                        <small class="text-muted">${item.plateThickness}</small>
                    </td>
                    <td><span class="badge bg-dark">${item.method}</span></td>
                    <td><code class="small">${item.acceptanceStd}</code></td>
                    <td><span class="badge bg-success"><i class="bi bi-check-circle"></i> ${item.result}</span></td>
                    <td>
                        <div>${item.inspector}</div>
                        <small class="text-muted"><i class="bi bi-shield-check"></i> ${item.tpiAgency}</small>
                    </td>
                </tr>
            `;
        });
        $('#qaqcTable tbody').html(html);
    }

    function renderAll() {
        renderDashboard();
        renderClients('all');
        renderProductionJobs();
        renderInventory();
        renderDispatches();
        renderInvoices();
        renderQuotations();
        renderQAQC();
    }

    // Navigation Switcher (SPA tabs)
    function switchSection(targetId) {
        if (!targetId || targetId === '#') targetId = '#dashboard';
        targetId = targetId.replace('#', '');

        // Hide all views, show target view
        $('.app-view-section').addClass('d-none');
        const $target = $('#view-' + targetId);
        if ($target.length) {
            $target.removeClass('d-none');
        } else {
            $('#view-dashboard').removeClass('d-none');
            targetId = 'dashboard';
        }

        // Update active class on nav links
        $('.sidebar-nav-link').removeClass('active');
        $(`.sidebar-nav-link[href="#${targetId}"]`).addClass('active');

        // Scroll to top of content
        window.scrollTo({ top: 0, behavior: 'smooth' });

        // Trigger chart resize if dashboard
        if (targetId === 'dashboard') {
            setTimeout(renderDashboardCharts, 150);
        }
    }

    // Event Handlers
    $(document).ready(function () {
        initData();
        renderAll();

        // Handle URL Hash navigation
        const currentHash = window.location.hash;
        if (currentHash) {
            switchSection(currentHash);
        } else {
            switchSection('#dashboard');
        }

        $(window).on('hashchange', function () {
            switchSection(window.location.hash);
        });

        // Sidebar link clicks
        $('.sidebar-nav-link').on('click', function (e) {
            const href = $(this).attr('href');
            if (href && href.startsWith('#')) {
                e.preventDefault();
                window.location.hash = href;
                // on mobile, close sidebar after clicking
                if ($(window).width() < 992) {
                    $('#sidebar').removeClass('active');
                }
            }
        });

        // Mobile Sidebar Toggle
        $('#sidebarToggle').on('click', function (e) {
            e.preventDefault();
            $('#sidebar').toggleClass('active');
        });

        // Reset Data Button
        $('#btnResetDemoData').on('click', function () {
            resetData();
        });

        // Client Sector Filter Pills
        $('.client-filter-btn').on('click', function () {
            $('.client-filter-btn').removeClass('active');
            $(this).addClass('active');
            const sector = $(this).data('sector');
            renderClients(sector);
        });

        // Populate Client Dropdowns in Modals
        function populateClientDropdowns() {
            let options = '<option value="">-- Select Client / Plant --</option>';
            appData.clients.forEach(c => {
                options += `<option value="${c.name}" data-sector="${c.sectorLabel}" data-id="${c.id}">${c.name} (${c.sectorLabel})</option>`;
            });
            $('#jobClientSelect, #invClientSelect, #qtnClientSelect, #dcClientSelect').html(options);
        }
        populateClientDropdowns();

        // -------------------------------------------------------------
        // Modal: Create New Job Order (Fixes and showcases /production/new)
        // -------------------------------------------------------------
        $('#btnOpenNewJobModal, a[href="#new-job-order"]').on('click', function (e) {
            e.preventDefault();
            populateClientDropdowns();
            const nextJobNum = "JOB-2627-00" + (appData.productionJobs.length + 1);
            $('#newJobNo').val(nextJobNum);
            $('#newJobStartDate').val(new Date().toISOString().split('T')[0]);
            const targetDate = new Date();
            targetDate.setDate(targetDate.getDate() + 45);
            $('#newJobTargetDate').val(targetDate.toISOString().split('T')[0]);
            $('#newJobModal').modal('show');
        });

        $('#jobClientSelect').on('change', function () {
            const sector = $(this).find('option:selected').data('sector') || 'Power Plant';
            $('#newJobSector').val(sector);
        });

        $('#formCreateJob').on('submit', function (e) {
            e.preventDefault();
            const clientName = $('#jobClientSelect').val();
            const sector = $('#newJobSector').val() || 'Power Plant';
            const title = $('#newJobTitle').val();
            const tonnage = parseFloat($('#newJobTonnage').val()) || 10;
            const drawingNo = $('#newJobDrawingNo').val() || 'DWG-STD-' + Math.floor(1000 + Math.random() * 9000);
            const priority = $('#newJobPriority').val() || 'High';
            const assignedBay = $('#newJobBay').val() || 'Heavy Bay 1';
            const startDate = $('#newJobStartDate').val();
            const targetDate = $('#newJobTargetDate').val();
            const notes = $('#newJobNotes').val();

            const newJob = {
                id: Date.now(),
                jobNo: $('#newJobNo').val() || ("JOB-2627-00" + (appData.productionJobs.length + 1)),
                client: clientName,
                sector: sector,
                title: title,
                tonnage: tonnage,
                completedTonnage: 0,
                drawingNo: drawingNo,
                startDate: startDate,
                targetDate: targetDate,
                priority: priority,
                status: "In Progress",
                currentStage: "Raw Material Issue & Traceability Check",
                stageProgress: 10,
                assignedBay: assignedBay,
                stages: [
                    { name: "Raw Material Issue & Traceability Check", status: "in_progress", date: startDate },
                    { name: "CNC Oxy-Fuel Plate Cutting & Edge Prep", status: "pending", date: "Scheduled" },
                    { name: "Sub-assembly Fit-up & Tack Welding", status: "pending", date: "Scheduled" },
                    { name: "SAW & GMAW Full Penetration Welding", status: "pending", date: "Scheduled" },
                    { name: "NDT 100% Ultrasonic & Radiography Testing", status: "pending", date: "Scheduled" },
                    { name: "Surface Grit Blasting to SA 2.5", status: "pending", date: "Scheduled" },
                    { name: "High Build Epoxy & PU Finish Painting", status: "pending", date: "Scheduled" },
                    { name: "Final Client TPI Inspection & Clearance", status: "pending", date: "Scheduled" }
                ],
                notes: notes || "Newly created job order. Material requisition forwarded to Plate Yard."
            };

            appData.productionJobs.unshift(newJob);
            saveData();
            renderAll();
            $('#newJobModal').modal('hide');
            this.reset();
            showToast(`Fabrication Job Order ${newJob.jobNo} created successfully! (Zero 404s)`, 'success');
        });

        // View Job Traveler / Stages Modal
        $(document).on('click', '.view-job-btn', function () {
            const jobId = $(this).data('id');
            const job = appData.productionJobs.find(j => j.id == jobId);
            if (!job) return;

            $('#modalTravelerJobNo').text(job.jobNo);
            $('#modalTravelerTitle').text(job.title);
            $('#modalTravelerClient').text(job.client + " (" + job.sector + ")");
            $('#modalTravelerTonnage').text(job.tonnage + " MT (Completed: " + job.completedTonnage + " MT)");
            $('#modalTravelerDrawing').text(job.drawingNo);
            $('#modalTravelerBay').text(job.assignedBay);
            $('#modalTravelerNotes').text(job.notes);

            let stagesHtml = '';
            (job.stages || []).forEach((st, idx) => {
                const isDone = st.status === 'completed';
                const isInProg = st.status === 'in_progress';
                const badge = isDone ? 'bg-success' : (isInProg ? 'bg-warning text-dark' : 'bg-secondary');
                const icon = isDone ? 'bi-check-circle-fill text-success' : (isInProg ? 'bi-arrow-repeat text-warning' : 'bi-circle text-muted');

                stagesHtml += `
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                            <i class="bi ${icon} fs-5 me-3"></i>
                            <div>
                                <div class="fw-bold ${isDone ? 'text-decoration-line-through text-muted' : ''}">Stage ${idx + 1}: ${st.name}</div>
                                <small class="text-muted"><i class="bi bi-calendar"></i> ${st.date}</small>
                            </div>
                        </div>
                        <span class="badge ${badge} text-uppercase">${st.status.replace('_', ' ')}</span>
                    </li>
                `;
            });
            $('#modalTravelerStagesList').html(stagesHtml);
            $('#jobTravelerModal').modal('show');
        });

        // Advance Job Stage Action
        $(document).on('click', '.advance-stage-btn', function () {
            const jobId = $(this).data('id');
            const job = appData.productionJobs.find(j => j.id == jobId);
            if (!job) return;

            const stagesList = job.stages || [];
            let advanced = false;

            for (let i = 0; i < stagesList.length; i++) {
                if (stagesList[i].status === 'in_progress') {
                    stagesList[i].status = 'completed';
                    if (i + 1 < stagesList.length) {
                        stagesList[i + 1].status = 'in_progress';
                        stagesList[i + 1].date = new Date().toISOString().split('T')[0];
                        job.currentStage = stagesList[i + 1].name;
                        job.stageProgress = Math.min(100, Math.round(((i + 2) / stagesList.length) * 100));
                        job.completedTonnage = (job.tonnage * (job.stageProgress / 100)).toFixed(1);
                    } else {
                        job.currentStage = 'All Stages Completed';
                        job.status = 'Completed';
                        job.stageProgress = 100;
                        job.completedTonnage = job.tonnage;
                    }
                    advanced = true;
                    break;
                }
            }

            if (!advanced && stagesList.length > 0 && stagesList[0].status === 'pending') {
                stagesList[0].status = 'in_progress';
                job.currentStage = stagesList[0].name;
                job.stageProgress = 15;
            }

            saveData();
            renderAll();
            showToast(`${job.jobNo} advanced to stage: ${job.currentStage}`, 'info');
        });

        // -------------------------------------------------------------
        // Modal: Add New Client
        // -------------------------------------------------------------
        $('#btnOpenNewClientModal').on('click', function () {
            const nextCode = "CLI-" + (appData.clients.length + 1).toString().padStart(3, '0');
            $('#newClientCode').val(nextCode);
            $('#newClientModal').modal('show');
        });

        $('#formCreateClient').on('submit', function (e) {
            e.preventDefault();
            const sector = $('#newClientSector').val();
            const sectorLabel = sector === 'power_plant' ? 'Power Plant' : (sector === 'sugar_mill' ? 'Sugar Mill' : 'Cement Plant');

            const newClient = {
                id: Date.now(),
                code: $('#newClientCode').val() || ("CLI-" + (appData.clients.length + 1)),
                name: $('#newClientName').val(),
                sector: sector,
                sectorLabel: sectorLabel,
                contact: $('#newClientContact').val(),
                phone: $('#newClientPhone').val(),
                email: $('#newClientEmail').val(),
                city: $('#newClientCity').val(),
                state: $('#newClientState').val(),
                gstin: $('#newClientGstin').val() || '08AAACX0000X1Z1',
                creditLimit: parseFloat($('#newClientCredit').val()) || 10000000,
                activeJobs: 0,
                notes: $('#newClientNotes').val() || 'Industrial heavy steel fabrication buyer'
            };

            appData.clients.unshift(newClient);
            saveData();
            populateClientDropdowns();
            renderClients('all');
            renderDashboard();
            $('#newClientModal').modal('hide');
            this.reset();
            showToast(`Client "${newClient.name}" added successfully!`, 'success');
        });

        // View Client Details Modal
        $(document).on('click', '.view-client-btn', function () {
            const id = $(this).data('id');
            const client = appData.clients.find(c => c.id == id);
            if (!client) return;

            $('#clientDetailName').text(client.name);
            $('#clientDetailCode').text(client.code);
            $('#clientDetailSector').text(client.sectorLabel);
            $('#clientDetailGstin').text(client.gstin);
            $('#clientDetailContact').text(client.contact);
            $('#clientDetailPhone').text(client.phone);
            $('#clientDetailEmail').text(client.email);
            $('#clientDetailAddress').text(client.city + ", " + client.state);
            $('#clientDetailCredit').text(formatINR(client.creditLimit));
            $('#clientDetailNotes').text(client.notes);

            $('#clientDetailsModal').modal('show');
        });

        // -------------------------------------------------------------
        // Modal: Record GRN (Inward Plate/Section Material)
        // -------------------------------------------------------------
        $('#btnOpenNewGRNModal').on('click', function () {
            const nextCode = "PL-" + Math.floor(1000 + Math.random() * 9000);
            $('#newMatCode').val(nextCode);
            $('#newGRNModal').modal('show');
        });

        $('#formCreateGRN').on('submit', function (e) {
            e.preventDefault();
            const newItem = {
                id: Date.now(),
                itemCode: $('#newMatCode').val(),
                name: $('#newMatName').val(),
                category: $('#newMatCategory').val(),
                mill: $('#newMatMill').val(),
                heatNo: $('#newMatHeatNo').val(),
                mtcNo: $('#newMatMtcNo').val(),
                bay: $('#newMatBay').val(),
                stockMT: parseFloat($('#newMatTonnage').val()) || 10,
                unit: "MT",
                minStockMT: 15.0,
                ratePerMT: parseFloat($('#newMatRate').val()) || 65000,
                status: "Available"
            };

            appData.inventory.unshift(newItem);
            saveData();
            renderInventory();
            renderDashboard();
            $('#newGRNModal').modal('hide');
            this.reset();
            showToast(`Inward GRN recorded for Heat No: ${newItem.heatNo} (${newItem.stockMT} MT)`, 'success');
        });

        // -------------------------------------------------------------
        // Modal: Generate Delivery Challan (Dispatch)
        // -------------------------------------------------------------
        $('#btnOpenNewDCModal').on('click', function () {
            populateClientDropdowns();
            const nextDC = "DC-2627-00" + (appData.dispatches.length + 1);
            $('#newDCNo').val(nextDC);
            $('#newDCDate').val(new Date().toISOString().split('T')[0]);
            $('#newEwayBill').val("2418" + Math.floor(10000000 + Math.random() * 90000000));
            $('#newDCModal').modal('show');
        });

        $('#formCreateDC').on('submit', function (e) {
            e.preventDefault();
            const gross = parseFloat($('#newDCGrossWt').val()) || 35.0;
            const tare = parseFloat($('#newDCTareWt').val()) || 15.0;
            const net = parseFloat((gross - tare).toFixed(2));

            const newDC = {
                id: Date.now(),
                dcNo: $('#newDCNo').val(),
                date: $('#newDCDate').val(),
                jobNo: $('#newDCJobNo').val() || "JOB-2627-001",
                client: $('#dcClientSelect').val(),
                destination: $('#newDCDestination').val(),
                transporter: $('#newDCTransporter').val(),
                vehicleNo: $('#newDCVehicle').val(),
                driverName: $('#newDCDriverName').val(),
                driverPhone: $('#newDCDriverPhone').val(),
                lrNo: "LR-" + Math.floor(1000 + Math.random() * 9000),
                ewayBill: $('#newEwayBill').val(),
                grossWt: gross,
                tareWt: tare,
                netWt: net,
                packages: parseInt($('#newDCPackages').val()) || 5,
                status: "Dispatched",
                remarks: $('#newDCRemarks').val() || "Consignment verified and released with gate pass."
            };

            appData.dispatches.unshift(newDC);
            saveData();
            renderDispatches();
            renderDashboard();
            $('#newDCModal').modal('hide');
            this.reset();
            showToast(`Delivery Challan ${newDC.dcNo} generated for ${newDC.netWt} MT!`, 'success');
        });

        // Print Delivery Challan Modal
        $(document).on('click', '.print-dc-btn', function () {
            const dcId = $(this).data('id');
            const dc = appData.dispatches.find(d => d.id == dcId);
            if (!dc) return;

            $('#printDcNumber').text(dc.dcNo);
            $('#printDcDate').text(dc.date);
            $('#printDcEway').text(dc.ewayBill);
            $('#printDcClient').text(dc.client);
            $('#printDcDest').text(dc.destination);
            $('#printDcTransporter').text(dc.transporter);
            $('#printDcVehicle').text(dc.vehicleNo);
            $('#printDcDriver').text(dc.driverName + " (" + dc.driverPhone + ")");
            $('#printDcGross').text(dc.grossWt + " MT");
            $('#printDcTare').text(dc.tareWt + " MT");
            $('#printDcNet').text(dc.netWt + " MT");
            $('#printDcPackages').text(dc.packages + " Bundles / Assemblies");
            $('#printDcJobRef').text(dc.jobNo);
            $('#printDcRemarks').text(dc.remarks);

            $('#printDCModal').modal('show');
        });

        // -------------------------------------------------------------
        // Modal: Generate GST Tax Invoice
        // -------------------------------------------------------------
        $('#btnOpenNewInvoiceModal').on('click', function () {
            populateClientDropdowns();
            const nextInv = "INV-2627-00" + (appData.invoices.length + 1);
            $('#newInvNo').val(nextInv);
            $('#newInvDate').val(new Date().toISOString().split('T')[0]);
            $('#newInvoiceModal').modal('show');
        });

        $('#newInvWeight, #newInvRate, #newInvIsInterstate').on('input change', function () {
            const wt = parseFloat($('#newInvWeight').val()) || 0;
            const rate = parseFloat($('#newInvRate').val()) || 0;
            const taxable = wt * rate;
            const isInter = $('#newInvIsInterstate').is(':checked');

            let cgst = 0, sgst = 0, igst = 0;
            if (isInter) {
                igst = taxable * 0.18;
            } else {
                cgst = taxable * 0.09;
                sgst = taxable * 0.09;
            }
            const total = taxable + cgst + sgst + igst;

            $('#calcTaxable').text(formatINR(taxable));
            $('#calcTax').text(formatINR(cgst + sgst + igst));
            $('#calcTotal').text(formatINR(total));
        });

        $('#formCreateInvoice').on('submit', function (e) {
            e.preventDefault();
            const clientName = $('#invClientSelect').val();
            const clientObj = appData.clients.find(c => c.name === clientName) || {};
            const wt = parseFloat($('#newInvWeight').val()) || 10;
            const rate = parseFloat($('#newInvRate').val()) || 85000;
            const taxable = wt * rate;
            const isInter = $('#newInvIsInterstate').is(':checked');

            let cgst = 0, sgst = 0, igst = 0;
            if (isInter) {
                igst = taxable * 0.18;
            } else {
                cgst = taxable * 0.09;
                sgst = taxable * 0.09;
            }
            const total = taxable + cgst + sgst + igst;

            const newInv = {
                id: Date.now(),
                invNo: $('#newInvNo').val(),
                date: $('#newInvDate').val(),
                client: clientName,
                clientGstin: clientObj.gstin || '08AAACX9999K1Z1',
                state: clientObj.state || 'Rajasthan',
                supplyType: isInter ? 'Inter-State (IGST 18%)' : 'Intra-State (CGST 9% + SGST 9%)',
                hsn: "730890",
                description: $('#newInvDescription').val() || "Fabricated Steel Structures for Industrial Plant",
                weightMT: wt,
                ratePerMT: rate,
                taxableAmount: taxable,
                cgst: cgst,
                sgst: sgst,
                igst: igst,
                totalAmount: total,
                dcRef: $('#newInvDcRef').val() || "DC-2627-001",
                status: "Sent",
                paidAmount: 0,
                dueAmount: total,
                irn: "irn" + Math.random().toString(16).substr(2) + Math.random().toString(16).substr(2)
            };

            appData.invoices.unshift(newInv);
            saveData();
            renderInvoices();
            renderDashboard();
            $('#newInvoiceModal').modal('hide');
            this.reset();
            showToast(`GST Tax Invoice ${newInv.invNo} generated for ${formatINR(newInv.totalAmount)}!`, 'success');
        });

        // Print Tax Invoice Modal
        $(document).on('click', '.print-inv-btn', function () {
            const invId = $(this).data('id');
            const inv = appData.invoices.find(i => i.id == invId);
            if (!inv) return;

            $('#printInvNo').text(inv.invNo);
            $('#printInvDate').text(inv.date);
            $('#printInvClient').text(inv.client);
            $('#printInvGstin').text(inv.clientGstin);
            $('#printInvState').text(inv.state);
            $('#printInvDcRef').text(inv.dcRef);
            $('#printInvDesc').text(inv.description);
            $('#printInvWeight').text(inv.weightMT + " MT");
            $('#printInvRate').text(formatINR(inv.ratePerMT));
            $('#printInvTaxable').text(formatINR(inv.taxableAmount));

            if (inv.igst > 0) {
                $('#printInvTaxBreakup').html(`
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Integrated GST (IGST @ 18%):</td>
                        <td class="text-end fw-bold">${formatINR(inv.igst)}</td>
                    </tr>
                `);
            } else {
                $('#printInvTaxBreakup').html(`
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Central GST (CGST @ 9%):</td>
                        <td class="text-end fw-bold">${formatINR(inv.cgst)}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-end fw-bold">State GST (SGST @ 9%):</td>
                        <td class="text-end fw-bold">${formatINR(inv.sgst)}</td>
                    </tr>
                `);
            }

            $('#printInvTotal').text(formatINR(inv.totalAmount));
            $('#printInvModal').modal('show');
        });

        // Standalone Print Window Trigger
        $('.do-browser-print').on('click', function () {
            window.print();
        });
    });

    // Expose helpers globally
    window.SteelERP = {
        getData: () => appData,
        resetData: resetData,
        switchSection: switchSection
    };

})();
