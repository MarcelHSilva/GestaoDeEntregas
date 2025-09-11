# Sistema de Gestão de Entregas

Sistema completo para gestão de entregas de moto, desenvolvido em Laravel.

## 🚀 Funcionalidades

### 📦 Gestão de Entregas
- Cadastro de entregas diárias
- Controle de quilometragem (inicial e final)
- Registro de quantidade de entregas e valor por entrega
- Cálculo automático de consumo de combustível
- Controle de custos e lucros

### 🔧 Gestão de Manutenções
- Cadastro de manutenções realizadas
- Controle de custos de manutenção
- Registro de quilometragem da manutenção
- Integração com detalhes de entregas

### 📊 Relatórios
- Relatórios semanais
- Relatórios mensais
- Relatórios personalizados por período
- Análise de consumo e custos
- Cálculo de margem de lucro

### 💡 Recursos Especiais
- **Integração Entregas x Manutenções**: Visualização de manutenções realizadas no mesmo dia da entrega
- **Cálculo de Lucro Real**: Desconto automático dos custos de manutenção do lucro líquido
- **Interface Responsiva**: Design moderno e adaptável
- **Paginação Customizada**: Navegação otimizada entre registros

## 🛠️ Tecnologias Utilizadas

- **Laravel 11** - Framework PHP
- **SQLite** - Banco de dados
- **Bootstrap 5** - Framework CSS
- **Bootstrap Icons** - Ícones
- **Carbon** - Manipulação de datas

## 📋 Pré-requisitos

- PHP 8.2 ou superior
- Composer
- Node.js (opcional, para assets)

## 🚀 Instalação

1. Clone o repositório:
```bash
git clone https://github.com/MarcelHSilva/GestaoDeEntregas.git
cd GestaoDeEntregas
```

2. Instale as dependências:
```bash
composer install
```

3. Configure o ambiente:
```bash
cp .env.example .env
php artisan key:generate
```

4. Execute as migrações:
```bash
php artisan migrate
```

5. Inicie o servidor:
```bash
php artisan serve
```

6. Acesse: `http://localhost:8000`

## 📱 Como Usar

### Cadastro de Entregas
1. Acesse "Entregas" no menu
2. Clique em "Nova Entrega"
3. Preencha os dados: data, KM inicial/final, quantidade de entregas, etc.
4. O sistema calculará automaticamente o consumo e lucros

### Cadastro de Manutenções
1. Acesse "Manutenções" no menu
2. Clique em "Nova Manutenção"
3. Registre a data, descrição, custo e quilometragem

### Visualização Integrada
- Ao visualizar os detalhes de uma entrega, as manutenções do mesmo dia são exibidas automaticamente
- O lucro real é calculado descontando os custos de manutenção

### Relatórios
1. Acesse "Relatórios" no menu
2. Escolha entre semanal, mensal ou personalizado
3. Visualize gráficos e análises detalhadas

## 🎯 Principais Recursos

- ✅ **Gestão Completa**: Entregas, manutenções e relatórios em um só lugar
- ✅ **Cálculos Automáticos**: Consumo, custos e lucros calculados automaticamente
- ✅ **Interface Intuitiva**: Design limpo e fácil de usar
- ✅ **Relatórios Detalhados**: Análises completas de performance
- ✅ **Integração Inteligente**: Manutenções vinculadas às entregas por data

## 📄 Licença

Este projeto está sob a licença MIT.

## 👨‍💻 Desenvolvedor

Desenvolvido por [Marcel Silva](https://github.com/MarcelHSilva)

---

⭐ Se este projeto foi útil para você, considere dar uma estrela no repositório!
