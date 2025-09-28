using Microsoft.EntityFrameworkCore;
using TechFixApplication.Models.Entities;




namespace TechFixApplication.Utili
{
    public class DBConnection : DbContext
    {
        public DBConnection(DbContextOptions options) : base(options)
        {

        }

        public DbSet<Staff> Staff { get; set; }

        public DbSet<Supplier> Suppliers { get; set; }

        public DbSet<Inventory> Inventorys { get; set; }

        public DbSet<Quotation> Quotations { get; set; }

        public DbSet<QuotationRequest> QuotationRequests { get; set; }
        
        public DbSet<Orders> Orders { get; set; }
    }
}
