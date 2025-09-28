using Microsoft.AspNetCore.Mvc;

namespace TechFixApplication.Models
{
    public class UpdateStaffDto
    {
        public required string Name { get; set; }

        public required string Email { get; set; }

        public string? Phone { get; set; }

        public required string Password { get; set; }
    }

    }
