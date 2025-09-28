namespace TechFixApplication.Models
{
    public class AddSupplierDto
    {
        public required string Company { get; set; }

        public required string Name { get; set; }

        public required string Email { get; set; }

        public required string Location { get; set; }

        public string? Phone { get; set; }

        public string? Password { get; set; }
    }
}
