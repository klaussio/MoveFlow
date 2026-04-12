-- ============================================
-- MoveFlow — Seed Data
-- ============================================

USE moveflow;

-- ============================================
-- Admin User (password: admin123)
-- ============================================
INSERT INTO users (name, email, password_hash, role) VALUES
('Administrador', 'admin@moveflow.com', '$2y$10$/lEFbxBCltKhbtbJD0V5BuCHVi9ZrvCWuZ4ByRc7ZKR434n6hearq', 'admin');

-- ============================================
-- Drivers (10)
-- ============================================
INSERT INTO drivers (name, email, phone, cpf, cnh, vehicle_plate, vehicle_model, vehicle_color, status, rating) VALUES
('Carlos Eduardo Silva', 'carlos.silva@email.com', '11987654321', '12345678901', '04587231205', 'BRA-2E19', 'Toyota Corolla', 'Prata', 'available', 4.85),
('Ana Paula Oliveira', 'ana.oliveira@email.com', '11976543210', '23456789012', '03298745612', 'RIO-3F45', 'Honda Civic', 'Preto', 'available', 4.92),
('Roberto Almeida Santos', 'roberto.santos@email.com', '21987651234', '34567890123', '05612378904', 'SPO-1A23', 'Volkswagen Virtus', 'Branco', 'busy', 4.70),
('Fernanda Costa Lima', 'fernanda.lima@email.com', '11965432109', '45678901234', '07845612309', 'MFL-4G78', 'Chevrolet Onix', 'Cinza', 'available', 4.88),
('Marcos Pereira Souza', 'marcos.souza@email.com', '21976542310', '56789012345', '01234567890', 'ABC-5H12', 'Hyundai HB20', 'Vermelho', 'offline', 4.65),
('Juliana Ferreira Dias', 'juliana.dias@email.com', '11954321098', '67890123456', '09876543210', 'DEF-6I34', 'Fiat Argo', 'Azul', 'available', 4.95),
('Ricardo Mendes Neto', 'ricardo.neto@email.com', '21965431209', '78901234567', '08765432109', 'GHI-7J56', 'Nissan Kicks', 'Branco', 'available', 4.78),
('Patricia Gomes Rocha', 'patricia.rocha@email.com', '11943210987', '89012345678', '07654321098', 'JKL-8K78', 'Jeep Renegade', 'Preto', 'busy', 4.82),
('Lucas Barbosa Teixeira', 'lucas.teixeira@email.com', '21954320198', '90123456789', '06543210987', 'MNO-9L90', 'Renault Kwid', 'Branco', 'available', 4.60),
('Camila Ribeiro Nunes', 'camila.nunes@email.com', '11932109876', '01234567890', '05432109876', 'PQR-0M12', 'Chevrolet Tracker', 'Prata', 'offline', 4.90);

-- ============================================
-- Passengers (15)
-- ============================================
INSERT INTO passengers (name, email, phone, cpf) VALUES
('João Pedro Martins', 'joao.martins@email.com', '11912345678', '11122233344'),
('Maria Luiza Andrade', 'maria.andrade@email.com', '11923456789', '22233344455'),
('Pedro Henrique Campos', 'pedro.campos@email.com', '21934567890', '33344455566'),
('Isabela Santos Cruz', 'isabela.cruz@email.com', '11945678901', '44455566677'),
('Gabriel Oliveira Ramos', 'gabriel.ramos@email.com', '21956789012', '55566677788'),
('Larissa Moreira Costa', 'larissa.costa@email.com', '11967890123', '66677788899'),
('Rafael Lima Carvalho', 'rafael.carvalho@email.com', '21978901234', '77788899900'),
('Bianca Ferreira Lopes', 'bianca.lopes@email.com', '11989012345', '88899900011'),
('Thiago Alves Pereira', 'thiago.pereira@email.com', '21990123456', '99900011122'),
('Amanda Souza Vieira', 'amanda.vieira@email.com', '11901234567', '00011122233'),
('Diego Nascimento Reis', 'diego.reis@email.com', '21912340567', '11223344556'),
('Carolina Machado Pinto', 'carolina.pinto@email.com', '11923450678', '22334455667'),
('Felipe Correia Braga', 'felipe.braga@email.com', '21934560789', '33445566778'),
('Natália Dias Monteiro', 'natalia.monteiro@email.com', '11945670890', '44556677889'),
('Bruno Teixeira Freitas', 'bruno.freitas@email.com', '21956780901', '55667788990');

-- ============================================
-- Rides (30) — Various statuses and dates
-- ============================================
INSERT INTO rides (passenger_id, driver_id, origin_address, destination_address, status, distance_km, estimated_duration_min, fare, requested_at, accepted_at, started_at, completed_at, cancelled_at, cancel_reason, created_at) VALUES
-- Completed rides (recent)
(1, 1, 'Av. Paulista, 1000 - São Paulo', 'Rua Augusta, 500 - São Paulo', 'completed', 3.2, 12, 16.80, DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY), NULL, NULL, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(2, 2, 'Shopping Ibirapuera - São Paulo', 'Aeroporto de Congonhas - São Paulo', 'completed', 8.5, 25, 36.25, DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY), NULL, NULL, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(3, 4, 'Estação da Luz - São Paulo', 'Liberdade - São Paulo', 'completed', 2.1, 10, 14.25, DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY), NULL, NULL, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(4, 6, 'Pinheiros - São Paulo', 'Vila Madalena - São Paulo', 'completed', 4.0, 15, 21.00, DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY), NULL, NULL, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(5, 7, 'Mooca - São Paulo', 'Tatuapé - São Paulo', 'completed', 5.3, 18, 25.45, DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY), NULL, NULL, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(6, 1, 'Itaim Bibi - São Paulo', 'Brooklin - São Paulo', 'completed', 3.8, 14, 20.10, DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY), NULL, NULL, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(7, 2, 'Consolação - São Paulo', 'Bela Vista - São Paulo', 'completed', 1.8, 8, 12.70, DATE_SUB(NOW(), INTERVAL 4 DAY), DATE_SUB(NOW(), INTERVAL 4 DAY), DATE_SUB(NOW(), INTERVAL 4 DAY), DATE_SUB(NOW(), INTERVAL 4 DAY), NULL, NULL, DATE_SUB(NOW(), INTERVAL 4 DAY)),
(8, 4, 'Lapa - São Paulo', 'Barra Funda - São Paulo', 'completed', 2.5, 10, 15.25, DATE_SUB(NOW(), INTERVAL 4 DAY), DATE_SUB(NOW(), INTERVAL 4 DAY), DATE_SUB(NOW(), INTERVAL 4 DAY), DATE_SUB(NOW(), INTERVAL 4 DAY), NULL, NULL, DATE_SUB(NOW(), INTERVAL 4 DAY)),
(9, 6, 'Santana - São Paulo', 'Tucuruvi - São Paulo', 'completed', 4.2, 16, 21.90, DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY), NULL, NULL, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(10, 7, 'Morumbi - São Paulo', 'Santo Amaro - São Paulo', 'completed', 6.1, 22, 29.05, DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY), NULL, NULL, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(11, 1, 'Vila Mariana - São Paulo', 'Saúde - São Paulo', 'completed', 2.0, 9, 13.60, DATE_SUB(NOW(), INTERVAL 6 DAY), DATE_SUB(NOW(), INTERVAL 6 DAY), DATE_SUB(NOW(), INTERVAL 6 DAY), DATE_SUB(NOW(), INTERVAL 6 DAY), NULL, NULL, DATE_SUB(NOW(), INTERVAL 6 DAY)),
(12, 2, 'Perdizes - São Paulo', 'Pompeia - São Paulo', 'completed', 1.5, 7, 11.55, DATE_SUB(NOW(), INTERVAL 6 DAY), DATE_SUB(NOW(), INTERVAL 6 DAY), DATE_SUB(NOW(), INTERVAL 6 DAY), DATE_SUB(NOW(), INTERVAL 6 DAY), NULL, NULL, DATE_SUB(NOW(), INTERVAL 6 DAY)),
(13, 9, 'Campo Belo - São Paulo', 'Moema - São Paulo', 'completed', 3.0, 12, 17.30, DATE_SUB(NOW(), INTERVAL 7 DAY), DATE_SUB(NOW(), INTERVAL 7 DAY), DATE_SUB(NOW(), INTERVAL 7 DAY), DATE_SUB(NOW(), INTERVAL 7 DAY), NULL, NULL, DATE_SUB(NOW(), INTERVAL 7 DAY)),
(14, 4, 'Butantã - São Paulo', 'Pinheiros - São Paulo', 'completed', 5.0, 18, 24.70, DATE_SUB(NOW(), INTERVAL 7 DAY), DATE_SUB(NOW(), INTERVAL 7 DAY), DATE_SUB(NOW(), INTERVAL 7 DAY), DATE_SUB(NOW(), INTERVAL 7 DAY), NULL, NULL, DATE_SUB(NOW(), INTERVAL 7 DAY)),
(15, 6, 'Jardins - São Paulo', 'Cerqueira César - São Paulo', 'completed', 1.2, 6, 10.40, DATE_SUB(NOW(), INTERVAL 7 DAY), DATE_SUB(NOW(), INTERVAL 7 DAY), DATE_SUB(NOW(), INTERVAL 7 DAY), DATE_SUB(NOW(), INTERVAL 7 DAY), NULL, NULL, DATE_SUB(NOW(), INTERVAL 7 DAY)),

-- In progress rides
(1, 3, 'Av. Brigadeiro Faria Lima, 3000 - São Paulo', 'Rua Oscar Freire, 200 - São Paulo', 'in_progress', 4.5, 15, 20.25, DATE_SUB(NOW(), INTERVAL 30 MINUTE), DATE_SUB(NOW(), INTERVAL 25 MINUTE), DATE_SUB(NOW(), INTERVAL 20 MINUTE), NULL, NULL, NULL, DATE_SUB(NOW(), INTERVAL 30 MINUTE)),
(5, 8, 'Shopping Villa-Lobos - São Paulo', 'Parque do Ibirapuera - São Paulo', 'in_progress', 7.2, 22, 31.80, DATE_SUB(NOW(), INTERVAL 45 MINUTE), DATE_SUB(NOW(), INTERVAL 40 MINUTE), DATE_SUB(NOW(), INTERVAL 35 MINUTE), NULL, NULL, NULL, DATE_SUB(NOW(), INTERVAL 45 MINUTE)),

-- Accepted rides
(8, 1, 'Terminal Tietê - São Paulo', 'Brás - São Paulo', 'accepted', 3.5, 14, 19.35, DATE_SUB(NOW(), INTERVAL 10 MINUTE), DATE_SUB(NOW(), INTERVAL 5 MINUTE), NULL, NULL, NULL, NULL, DATE_SUB(NOW(), INTERVAL 10 MINUTE)),
(3, 4, 'Av. Rebouças, 1500 - São Paulo', 'Rua da Consolação, 800 - São Paulo', 'accepted', 2.8, 11, 16.40, DATE_SUB(NOW(), INTERVAL 8 MINUTE), DATE_SUB(NOW(), INTERVAL 3 MINUTE), NULL, NULL, NULL, NULL, DATE_SUB(NOW(), INTERVAL 8 MINUTE)),

-- Requested rides (waiting for driver)
(10, NULL, 'Largo da Batata - São Paulo', 'Av. Paulista, 2000 - São Paulo', 'requested', 5.5, 20, 26.75, DATE_SUB(NOW(), INTERVAL 2 MINUTE), NULL, NULL, NULL, NULL, NULL, DATE_SUB(NOW(), INTERVAL 2 MINUTE)),
(12, NULL, 'USP - São Paulo', 'Aeroporto de Guarulhos - São Paulo', 'requested', 32.0, 50, 105.00, DATE_SUB(NOW(), INTERVAL 1 MINUTE), NULL, NULL, NULL, NULL, NULL, DATE_SUB(NOW(), INTERVAL 1 MINUTE)),
(6, NULL, 'Rua 25 de Março - São Paulo', 'Shopping Eldorado - São Paulo', 'requested', 9.0, 28, 38.70, NOW(), NULL, NULL, NULL, NULL, NULL, NOW()),

-- Cancelled rides
(2, 1, 'Av. Interlagos, 4000 - São Paulo', 'Autódromo de Interlagos - São Paulo', 'cancelled', 6.0, 20, 28.00, DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY), NULL, NULL, DATE_SUB(NOW(), INTERVAL 2 DAY), 'Passageiro não encontrado no local', DATE_SUB(NOW(), INTERVAL 2 DAY)),
(7, 4, 'Rua XV de Novembro - São Paulo', 'Praça da Sé - São Paulo', 'cancelled', 1.0, 5, 9.50, DATE_SUB(NOW(), INTERVAL 3 DAY), NULL, NULL, NULL, DATE_SUB(NOW(), INTERVAL 3 DAY), 'Cancelada pelo passageiro', DATE_SUB(NOW(), INTERVAL 3 DAY)),
(9, NULL, 'Largo do Arouche - São Paulo', 'República - São Paulo', 'cancelled', 0.8, 4, 8.60, DATE_SUB(NOW(), INTERVAL 4 DAY), NULL, NULL, NULL, DATE_SUB(NOW(), INTERVAL 4 DAY), 'Tempo de espera excedido', DATE_SUB(NOW(), INTERVAL 4 DAY)),

-- More completed for chart data
(4, 7, 'Av. Rebouças - São Paulo', 'Rua Haddock Lobo - São Paulo', 'completed', 2.3, 9, 13.35, DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY), NULL, NULL, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(11, 9, 'Sacomã - São Paulo', 'Ipiranga - São Paulo', 'completed', 3.7, 13, 19.45, DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY), NULL, NULL, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(13, 2, 'Água Branca - São Paulo', 'Sumaré - São Paulo', 'completed', 4.5, 16, 22.65, DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY), NULL, NULL, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(14, 6, 'Vila Olímpia - São Paulo', 'Chácara Klabin - São Paulo', 'completed', 7.8, 24, 34.10, DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY), NULL, NULL, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(15, 1, 'Paraíso - São Paulo', 'Aclimação - São Paulo', 'completed', 2.6, 10, 15.50, DATE_SUB(NOW(), INTERVAL 6 DAY), DATE_SUB(NOW(), INTERVAL 6 DAY), DATE_SUB(NOW(), INTERVAL 6 DAY), DATE_SUB(NOW(), INTERVAL 6 DAY), NULL, NULL, DATE_SUB(NOW(), INTERVAL 6 DAY));
